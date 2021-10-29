<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Repository;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

interface AvataxConfigurationRepositoryInterface extends RepositoryInterface
{
    public function findOneByEnabled(): ?AvataxConfigurationInterface;
}
