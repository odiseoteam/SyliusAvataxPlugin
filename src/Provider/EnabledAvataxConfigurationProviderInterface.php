<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Provider;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;

interface EnabledAvataxConfigurationProviderInterface
{
    public function getConfiguration(): ?AvataxConfigurationInterface;
}
