<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface AvataxConfigurationSenderDataInterface extends ResourceInterface
{
    public function getProvinceCode(): ?string;

    public function setProvinceCode(?string $provinceCode): void;

    public function getCountryCode(): ?string;

    public function setCountryCode(?string $countryCode): void;

    public function getStreet(): ?string;

    public function setStreet(?string $street): void;

    public function getCity(): ?string;

    public function setCity(?string $city): void;

    public function getPostcode(): ?string;

    public function setPostcode(?string $postcode): void;
}
