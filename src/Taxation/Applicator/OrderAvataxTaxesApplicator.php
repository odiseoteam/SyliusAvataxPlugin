<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Taxation\Applicator;

use Avalara\DocumentType;
use Avalara\TransactionAddressType;
use Avalara\TransactionBuilder;
use Odiseo\SyliusAvataxPlugin\Api\AvataxClient;
use Odiseo\SyliusAvataxPlugin\Provider\EnabledAvataxConfigurationProviderInterface;
use Odiseo\SyliusAvataxPlugin\Resolver\OrderItemAvataxCodeResolverInterface;
use Odiseo\SyliusAvataxPlugin\Resolver\ShippingAvataxCodeResolverInterface;
use Sylius\Component\Addressing\Matcher\ZoneMatcherInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Model\Scope;
use Sylius\Component\Core\Model\ShopBillingDataInterface;
use Sylius\Component\Core\Provider\ZoneProviderInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;

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

        $taxes = $this->createAvataxTb($order)->create();

        foreach ($taxes->lines as $line) {
            if ('shipping' === $line->itemCode) {
                /** @var AdjustmentInterface $shippingTaxAdjustment */
                $shippingTaxAdjustment = $this->adjustmentFactory
                    ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, 'shipping_tax', intval($line->taxCalculated*100), false)
                ;
                $order->addAdjustment($shippingTaxAdjustment);
            } else {
                $matchItems = $order->getItems()->filter(function (OrderItemInterface $item) use ($line) {
                    return $line->itemCode === $item->getVariant()->getCode();
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
     * @param OrderItemUnitInterface $unit
     * @param int $taxAmount
     * @param string $label
     * @param bool $included
     */
    private function addAdjustment(OrderItemUnitInterface $unit, int $taxAmount, string $label, bool $included): void
    {
        $unitTaxAdjustment = $this->adjustmentFactory
            ->createWithData(AdjustmentInterface::TAX_ADJUSTMENT, $label, $taxAmount, $included)
        ;
        $unit->addAdjustment($unitTaxAdjustment);
    }

    /**
     * @param OrderInterface $order
     * @return TransactionBuilder
     */
    private function createAvataxTb(OrderInterface $order): TransactionBuilder
    {
        $tb = $this->createAvataxBaseTb($order);

        foreach ($order->getItems() as $item) {
            $quantity = $item->getQuantity();
            $variant = $item->getVariant();

            $tb->withLine($item->getTotal()/100, $quantity, $variant->getCode(), $this->orderItemAvataxCodeResolver->getTaxCode($item));
        }

        $tb->withLine($order->getShippingTotal()/100, 1, 'shipping', $this->shippingAvataxCodeResolver->getTaxCode($this->enabledAvataxConfigurationProvider->getConfiguration()));

        return $tb;
    }

    /**
     * @param OrderInterface $order
     * @return TransactionBuilder
     */
    private function createAvataxBaseTb(OrderInterface $order): TransactionBuilder
    {
        $customerCode = $order->getCustomer()?$order->getCustomer()->getEmail():'DEFAULT_CUSTOMER_CODE';
        $tb = new TransactionBuilder($this->avataxClient, "DEFAULT", DocumentType::C_SALESINVOICE, $customerCode);
        $tb->withCurrencyCode($order->getCurrencyCode());

        // From Address
        /** @var ChannelInterface $channel */
        $channel = $order->getChannel();
        $channelData = $channel->getShopBillingData();

        if ($channelData && $this->isValidAddress($channelData)) {
            $tb->withAddress(TransactionAddressType::C_SHIPFROM, $channelData->getStreet(), null, null,
                $channelData->getCity(), $channelData->getTaxId(), $channelData->getPostcode(), $channelData->getCountryCode()
            );
        }

        // To Address
        $shippingAddress = $order->getShippingAddress();

        $provinceCode = $shippingAddress->getProvinceName();
        if ('US' === $shippingAddress->getCountryCode()) {
            $provinceCode = substr($shippingAddress->getProvinceCode(), 3, 2);
        }

        $tb->withAddress(TransactionAddressType::C_SHIPTO, $shippingAddress->getStreet(), null, null,
            $shippingAddress->getCity(), $provinceCode, $shippingAddress->getPostcode(),
            $shippingAddress->getCountryCode()
        );

        return $tb;
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

        if (empty($zones)) {
            return false;
        }

        $avataxConfiguration = $this->enabledAvataxConfigurationProvider->getConfiguration();
        if (!in_array($avataxConfiguration->getZone(), $zones)) {
            return false;
        }

        return true;
    }

    private function isValidAddress(ShopBillingDataInterface $address): bool
    {
        return null !== $address->getStreet() && null !== $address->getCity() && null !== $address->getTaxId() &&
            null !== $address->getPostcode() && null !== $address->getCountryCode();
    }
}
