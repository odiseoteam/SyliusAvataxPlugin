<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class OrderItemAvataxCodeResolver implements OrderItemAvataxCodeResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTaxCode(OrderItemInterface $item): string
    {
        /** @var ProductVariantInterface $variant */
        $variant = $item->getVariant();
        /** @var AvataxAwareInterface $product */
        $product = $variant->getProduct();

        $taxCode = 'P0000000';
        if ($product->getAvataxCode() !== null) {
            /** @var string $taxCode */
            $taxCode = $product->getAvataxCode();
        }

        return $taxCode;
    }
}
