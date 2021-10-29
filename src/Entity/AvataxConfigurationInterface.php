<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Model\TimestampableInterface;
use Sylius\Component\Resource\Model\ToggleableInterface;

interface AvataxConfigurationInterface extends
    ResourceInterface,
    ToggleableInterface,
    TimestampableInterface
{
    public function getAppName(): ?string;

    public function setAppName(?string $appName): void;

    public function getAppVersion(): ?string;

    public function setAppVersion(?string $appVersion): void;

    public function getMachineName(): ?string;

    public function setMachineName(?string $machineName): void;

    public function isSandbox(): ?bool;

    public function setSandbox(?bool $sandbox): void;

    public function getAccountId(): ?int;

    public function setAccountId(?int $accountId): void;

    public function getLicenseKey(): ?string;

    public function setLicenseKey(?string $licenseKey): void;

    public function getShippingTaxCode(): ?string;

    public function setShippingTaxCode(?string $shippingTaxCode): void;

    public function getZone(): ?ZoneInterface;

    public function setZone(?ZoneInterface $zone): void;

    public function getSenderData(): ?AvataxConfigurationSenderDataInterface;

    public function setSenderData(?AvataxConfigurationSenderDataInterface $senderData): void;
}
