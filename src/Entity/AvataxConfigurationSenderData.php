<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

class AvataxConfigurationSenderData implements AvataxConfigurationSenderDataInterface
{
    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $provinceCode;

    /** @var string|null */
    protected $countryCode;

    /** @var string|null */
    protected $street;

    /** @var string|null */
    protected $city;

    /** @var string|null */
    protected $postcode;

    /**
     * {@inheritdoc}
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getProvinceCode(): ?string
    {
        return $this->provinceCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setProvinceCode(?string $provinceCode): void
    {
        $this->provinceCode = $provinceCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setCountryCode(?string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getStreet(): ?string
    {
        return $this->street;
    }

    /**
     * {@inheritdoc}
     */
    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    /**
     * {@inheritdoc}
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * {@inheritdoc}
     */
    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    /**
     * {@inheritdoc}
     */
    public function getPostcode(): ?string
    {
        return $this->postcode;
    }

    /**
     * {@inheritdoc}
     */
    public function setPostcode(?string $postcode): void
    {
        $this->postcode = $postcode;
    }
}
