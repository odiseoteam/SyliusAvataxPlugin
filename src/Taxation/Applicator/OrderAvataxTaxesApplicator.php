<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Taxation\Applicator;

use Avalara\DocumentType;
use Avalara\TransactionAddressType;
use Avalara\TransactionBuilder;
use Odiseo\SyliusAvataxPlugin\Api\AvataxClient;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationSenderDataInterface;
use Odiseo\SyliusAvataxPlugin\Provider\EnabledAvataxConfigurationProviderInterface;
use Odiseo\SyliusAvataxPlugin\Resolver\OrderItemAvataxCodeResolverInterface;
use Odiseo\SyliusAvataxPlugin\Resolver\ShippingAvataxCodeResolverInterface;
use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class OrderAvataxTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /** @var AvataxClient */
    private $avataxClient;

    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    /** @var ZoneProviderInterface */
    private $defaultTaxZoneProvider;

    /** @var ZoneMatcherInterface */
    private $zoneMatcher;

    /** @var EnabledAvataxConfigurationProviderInterface */
    private $enabledAvataxConfigurationProvider;

    /** @var OrderItemAvataxCodeResolverInterface */
    private $orderItemAvataxCodeResolver;

    /** @var ShippingAvataxCodeResolverInterface */
    private $shippingAvataxCodeResolver;

    public function __construct(
        AvataxClient $avaTaxClient,
        AdjustmentFactoryInterface $adjustmentFactory,
        ZoneProviderInterface $defaultTaxZoneProvider,
        ZoneMatcherInterface $zoneMatcher,
        EnabledAvataxConfigurationProviderInterface $enabledAvataxConfigurationProvider,
        OrderItemAvataxCodeResolverInterface $orderItemAvataxCodeResolver,
        ShippingAvataxCodeResolverInterface $shippingAvataxCodeResolver
    ) {
        $this->avataxClient = $avaTaxClient;
        $this->adjustmentFactory = $adjustmentFactory;
        $this->defaultTaxZoneProvider = $defaultTaxZoneProvider;
        $this->zoneMatcher = $zoneMatcher;
        $this->enabledAvataxConfigurationProvider = $enabledAvataxConfigurationProvider;
        $this->orderItemAvataxCodeResolver = $orderItemAvataxCodeResolver;
        $this->shippingAvataxCodeResolver = $shippingAvataxCodeResolver;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
        if (!$this->matchTaxZone($order)) {
            return;
        }

        $taxes = $this->createAvataxTransactionBuilder($order)->create();
        if (!isset($taxes->lines)) {
            /** @var string $message */
            $message = $taxes;

            throw new BadRequestHttpException($message);
        }

        foreach ($taxes->lines as $line) {
            if ('shipping' === $line->itemCode) {
                /** @var AdjustmentInterface $shippingTaxAdjustment */
                $shippingTaxAdjustment = $this->adjustmentFactory
                    ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, 'shipping_tax', intval($line->taxCalculated*100), false)
                ;
                $order->addAdjustment($shippingTaxAdjustment);
            } else {
                $matchItems = $order->getItems()->filter(function (OrderItemInterface $item) use ($line): bool {
                    /** @var ProductVariantInterface $variant */
                    $variant = $item->getVariant();

                    return $line->itemCode === $variant->getCode();
                });

                if (count($matchItems) > 0) {
                    /** @var OrderItemInterface $matchItem */
                    $matchItem = $matchItems->first();

                    foreach ($matchItem->getUnits() as $unit) {
                        $unitTaxAdjustment = $this->adjustmentFactory
                            ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, 'item_tax', intval($line->taxCalculated*100), false);

                        $unit->addAdjustment($unitTaxAdjustment);
                    }
                }
            }
        }
    }

    /**
     * @param OrderInterface $order
     * @return TransactionBuilder
     */
    private function createAvataxTransactionBuilder(OrderInterface $order): TransactionBuilder
    {
        $transactionBuilder = $this->createAvataxBaseTransactionBuilder($order);

        foreach ($order->getItems() as $item) {
            $quantity = $item->getQuantity();
            /** @var ProductVariantInterface $variant */
            $variant = $item->getVariant();

            $transactionBuilder->withLine(
                $item->getTotal() / 100,
                $quantity,
                (string) $variant->getCode(),
                $this->orderItemAvataxCodeResolver->getTaxCode($item)
            );
        }

        $transactionBuilder->withLine(
            $order->getShippingTotal() / 100,
            1,
            'shipping',
            $this->shippingAvataxCodeResolver->getTaxCode(
                $this->enabledAvataxConfigurationProvider->getConfiguration()
            )
        );

        return $transactionBuilder;
    }

    /**
     * @param OrderInterface $order
     * @return TransactionBuilder
     */
    private function createAvataxBaseTransactionBuilder(OrderInterface $order): TransactionBuilder
    {
        $customer = $order->getCustomer();

        $customerCode = $customer !== null ? $customer->getEmail() : 'DEFAULT_CUSTOMER_CODE';

        $transactionBuilder = new TransactionBuilder(
            $this->avataxClient,
            'DEFAULT',
            (string) DocumentType::C_SALESINVOICE,
            (string) $customerCode
        );

        $transactionBuilder->withCurrencyCode((string) $order->getCurrencyCode());

        $avataxConfiguration = $this->enabledAvataxConfigurationProvider->getConfiguration();
        $senderData = $avataxConfiguration->getSenderData();

        if ($senderData !== null && $this->isValidAddress($senderData)) {
            $transactionBuilder->withAddress(
                TransactionAddressType::C_SHIPFROM,
                $senderData->getStreet(),
                null,
                null,
                $senderData->getCity(),
                $senderData->getProvinceCode(),
                $senderData->getPostcode(),
                $senderData->getCountryCode()
            );
        }

        /** @var AddressInterface $shippingAddress */
        $shippingAddress = $order->getShippingAddress();

        $provinceCode = $shippingAddress->getProvinceName();
        if ($provinceCode === null) {
            $provinceCode = substr((string) $shippingAddress->getProvinceCode(), 3, 2);
        }

        $transactionBuilder->withAddress(
            TransactionAddressType::C_SHIPTO,
            $shippingAddress->getStreet(),
            null,
            null,
            $shippingAddress->getCity(),
            $provinceCode,
            $shippingAddress->getPostcode(),
            $shippingAddress->getCountryCode()
        );

        return $transactionBuilder;
    }

    /**
     * @param OrderInterface $order
     * @return bool
     */
    private function matchTaxZone(OrderInterface $order): bool
    {
        $billingAddress = $order->getBillingAddress();
        $zones = [];

        if (null !== $billingAddress) {
            $zones = $this->zoneMatcher->matchAll($billingAddress, Scope::TAX);
        } else {
            $zones[] = $this->defaultTaxZoneProvider->getZone($order);
        }

        if (count($zones) === 0) {
            return false;
        }

        $avataxConfiguration = $this->enabledAvataxConfigurationProvider->getConfiguration();
        if (!in_array($avataxConfiguration->getZone(), $zones, true)) {
            return false;
        }

        return true;
    }

    /**
     * @param AvataxConfigurationSenderDataInterface $senderData
     * @return bool
     */
    private function isValidAddress(AvataxConfigurationSenderDataInterface $senderData): bool
    {
        return null !== $senderData->getStreet() && null !== $senderData->getCity() && null !== $senderData->getProvinceCode() &&
            null !== $senderData->getPostcode() && null !== $senderData->getCountryCode();
    }
}
