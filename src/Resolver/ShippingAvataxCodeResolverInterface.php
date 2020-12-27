<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;

interface ShippingAvataxCodeResolverInterface
{
    /**
     * @param AvataxConfigurationInterface $configuration
     * @return string
     */
    public function getTaxCode(AvataxConfigurationInterface $configuration): string;
}
