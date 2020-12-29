<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

interface AvataxConfigurationSenderDataInterface extends ResourceInterface
{
    /**
     * @return string|null
     */
    public function getProvinceCode(): ?string;

    /**
     * @param string|null $provinceCode
     */
    public function setProvinceCode(?string $provinceCode): void;

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string;

    /**
     * @param string|null $countryCode
     */
    public function setCountryCode(?string $countryCode): void;

    /**
     * @return string|null
     */
    public function getStreet(): ?string;

    /**
     * @param string|null $street
     */
    public function setStreet(?string $street): void;

    /**
     * @return string|null
     */
    public function getCity(): ?string;

    /**
     * @param string|null $city
     */
    public function setCity(?string $city): void;

    /**
     * @return string|null
     */
    public function getPostcode(): ?string;

    /**
     * @param string|null $postcode
     */
    public function setPostcode(?string $postcode): void;
}
