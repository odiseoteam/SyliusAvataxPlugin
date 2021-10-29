<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

interface AvataxAwareInterface
{
    public function getAvataxCode(): ?string;

    public function setAvataxCode(?string $avataxCode): void;
}
