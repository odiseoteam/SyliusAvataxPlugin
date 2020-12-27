<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

interface AvataxAwareInterface
{
    /**
     * @return string|null
     */
    public function getAvataxCode(): ?string;

    /**
     * @param string|null $avataxCode
     */
    public function setAvataxCode(?string $avataxCode): void;
}
