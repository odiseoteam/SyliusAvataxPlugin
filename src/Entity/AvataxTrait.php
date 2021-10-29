<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

trait AvataxTrait
{
    protected ?string $avataxCode = null;

    public function getAvataxCode(): ?string
    {
        return $this->avataxCode;
    }

    public function setAvataxCode(?string $avataxCode): void
    {
        $this->avataxCode = $avataxCode;
    }
}
