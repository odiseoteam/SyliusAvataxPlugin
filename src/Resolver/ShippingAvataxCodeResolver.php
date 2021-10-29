<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;

final class ShippingAvataxCodeResolver implements ShippingAvataxCodeResolverInterface
{
    public function getTaxCode(AvataxConfigurationInterface $configuration): string
    {
        $taxCode = 'FR010000';
        if ($configuration->getShippingTaxCode() !== null) {
            /** @var string $taxCode */
            $taxCode = $configuration->getShippingTaxCode();
        }

        return $taxCode;
    }
}
