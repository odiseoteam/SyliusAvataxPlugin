<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Taxation\Applicator;

use Avalara\DocumentType;
use Avalara\TransactionAddressType;
use Avalara\TransactionBuilder;
use Odiseo\SyliusAvataxPlugin\Api\AvataxClient;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Core\Model\AdjustmentInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\OrderItemUnitInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Taxation\Applicator\OrderTaxesApplicatorInterface;
use Sylius\Component\Order\Factory\AdjustmentFactoryInterface;

final class OrderAvataxTaxesApplicator implements OrderTaxesApplicatorInterface
{
    /** @var AvataxClient */
    private $avataxClient;

    /** @var AdjustmentFactoryInterface */
    private $adjustmentFactory;

    public function __construct(
        AvataxClient $avaTaxClient,
        AdjustmentFactoryInterface $adjustmentFactory
    ) {
        $this->avataxClient = $avaTaxClient;
        $this->adjustmentFactory = $adjustmentFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply(OrderInterface $order, ZoneInterface $zone): void
    {
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

        $shipments = $order->getShipments();
        /** @var ShipmentInterface $firstShipment */
        $firstShipment = $shipments->first();

        foreach ($order->getItems() as $item) {
            $quantity = $item->getQuantity();
            $variant = $item->getVariant();
            /** @var ProductInterface $product */
            $product = $variant->getProduct();

            $tb->withLine($item->getTotal()/100, $quantity, $variant->getCode(), $product->getTaxCode());
        }

        $tb->withLine($order->getShippingTotal()/100, 1, 'shipping', $firstShipment->getTaxCode());

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
}
