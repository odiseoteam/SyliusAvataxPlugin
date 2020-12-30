<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;

final class ShippingAvataxCodeResolver implements ShippingAvataxCodeResolverInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTaxCode(AvataxConfigurationInterface $configuration): string
    {
        return $configuration->getShippingTaxCode() !== null ? $configuration->getShippingTaxCode() : 'FR010000';
    }
}
