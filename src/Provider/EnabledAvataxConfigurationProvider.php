<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Provider;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Odiseo\SyliusAvataxPlugin\Repository\AvataxConfigurationRepositoryInterface;

final class EnabledAvataxConfigurationProvider implements EnabledAvataxConfigurationProviderInterface
{
    public function __construct(
        private AvataxConfigurationRepositoryInterface $avataxConfigurationRepository,
    ) {
    }

    public function getConfiguration(): ?AvataxConfigurationInterface
    {
        return $this->avataxConfigurationRepository->findOneByEnabled();
    }
}
