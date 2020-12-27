<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

trait AvataxTrait
{
    /** @var string|null */
    protected $avataxCode;

    /**
     * @return string|null
     */
    public function getAvataxCode(): ?string
    {
        return $this->avataxCode;
    }

    /**
     * @param string|null $avataxCode
     */
    public function setAvataxCode(?string $avataxCode): void
    {
        $this->avataxCode = $avataxCode;
    }
}
