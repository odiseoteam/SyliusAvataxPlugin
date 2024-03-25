<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

class AvataxConfigurationSenderData implements AvataxConfigurationSenderDataInterface
{
    protected ?int $id = null;

    protected ?string $provinceCode = null;

    protected ?string $countryCode = null;

    protected ?string $street = null;

    protected ?string $city = null;

    protected ?string $postcode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    public function setProvinceCode(?string $provinceCode): void
    {
        $this->provinceCode = $provinceCode;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }
}
