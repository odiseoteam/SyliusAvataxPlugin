<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Entity;

use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Model\TimestampableTrait;
use Sylius\Component\Resource\Model\ToggleableTrait;

class AvataxConfiguration implements AvataxConfigurationInterface
{
    use TimestampableTrait;
    use ToggleableTrait;

    protected ?int $id = null;
    protected ?string $appName = null;
    protected ?string $appVersion = null;
    protected ?string $machineName = null;
    protected ?bool $sandbox = true;
    protected ?int $accountId = null;
    protected ?string $licenseKey = null;
    protected ?string $shippingTaxCode = null;
    protected ?ZoneInterface $zone = null;
    protected ?AvataxConfigurationSenderDataInterface $senderData = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppName(): ?string
    {
        return $this->appName;
    }

    public function setAppName(?string $appName): void
    {
        $this->appName = $appName;
    }

    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    public function setAppVersion(?string $appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    public function getMachineName(): ?string
    {
        return $this->machineName;
    }

    public function setMachineName(?string $machineName): void
    {
        $this->machineName = $machineName;
    }

    public function isSandbox(): ?bool
    {
        return $this->sandbox;
    }

    public function setSandbox(?bool $sandbox): void
    {
        $this->sandbox = (bool) $sandbox;
    }

    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    public function setAccountId(?int $accountId): void
    {
        $this->accountId = $accountId;
    }

    public function getLicenseKey(): ?string
    {
        return $this->licenseKey;
    }

    public function setLicenseKey(?string $licenseKey): void
    {
        $this->licenseKey = $licenseKey;
    }

    public function getShippingTaxCode(): ?string
    {
        return $this->shippingTaxCode;
    }

    public function setShippingTaxCode(?string $shippingTaxCode): void
    {
        $this->shippingTaxCode = $shippingTaxCode;
    }

    public function getZone(): ?ZoneInterface
    {
        return $this->zone;
    }

    public function setZone(?ZoneInterface $zone): void
    {
        $this->zone = $zone;
    }

    public function getSenderData(): ?AvataxConfigurationSenderDataInterface
    {
        return $this->senderData;
    }

    public function setSenderData(?AvataxConfigurationSenderDataInterface $senderData): void
    {
        $this->senderData = $senderData;
    }
}
