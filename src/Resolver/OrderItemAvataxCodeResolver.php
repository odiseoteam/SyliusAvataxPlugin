<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

final class OrderItemAvataxCodeResolver implements OrderItemAvataxCodeResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTaxCode(OrderItemInterface $item): string
    {
        $variant = $item->getVariant();
        /** @var AvataxAwareInterface $product */
        $product = $variant->getProduct();

        return $product->getAvataxCode() ?: 'P0000000';
    }
}
