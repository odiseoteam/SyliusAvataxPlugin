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

    /** @var int|null */
    protected $id;

    /** @var string|null */
    protected $appName;

    /** @var string|null */
    protected $appVersion;

    /** @var string|null */
    protected $machineName;

    /** @var bool|null */
    protected $sandbox = true;

    /** @var int|null */
    protected $accountId;

    /** @var string|null */
    protected $licenseKey;

    /** @var string|null */
    protected $shippingTaxCode;

    /** @var ZoneInterface|null */
    protected $zone;

    /** @var AvataxConfigurationSenderDataInterface|null */
    protected $senderData;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
    public function getAppName(): ?string
    {
        return $this->appName;
    }

    /**
     * {@inheritdoc}
     */
    public function setAppName(?string $appName): void
    {
        $this->appName = $appName;
    }

    /**
     * {@inheritdoc}
     */
    public function getAppVersion(): ?string
    {
        return $this->appVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function setAppVersion(?string $appVersion): void
    {
        $this->appVersion = $appVersion;
    }

    /**
     * {@inheritdoc}
     */
    public function getMachineName(): ?string
    {
        return $this->machineName;
    }

    /**
     * {@inheritdoc}
     */
    public function setMachineName(?string $machineName): void
    {
        $this->machineName = $machineName;
    }

    /**
     * {@inheritdoc}
     */
    public function isSandbox(): ?bool
    {
        return $this->sandbox;
    }

    /**
     * {@inheritdoc}
     */
    public function setSandbox(?bool $sandbox): void
    {
        $this->sandbox = (bool) $sandbox;
    }

    /**
     * {@inheritdoc}
     */
    public function getAccountId(): ?int
    {
        return $this->accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function setAccountId(?int $accountId): void
    {
        $this->accountId = $accountId;
    }

    /**
     * {@inheritdoc}
     */
    public function getLicenseKey(): ?string
    {
        return $this->licenseKey;
    }

    /**
     * {@inheritdoc}
     */
    public function setLicenseKey(?string $licenseKey): void
    {
        $this->licenseKey = $licenseKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingTaxCode(): ?string
    {
        return $this->shippingTaxCode;
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingTaxCode(?string $shippingTaxCode): void
    {
        $this->shippingTaxCode = $shippingTaxCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getZone(): ?ZoneInterface
    {
        return $this->zone;
    }

    /**
     * {@inheritdoc}
     */
    public function setZone(?ZoneInterface $zone): void
    {
        $this->zone = $zone;
    }

    /**
     * {@inheritdoc}
     */
    public function getSenderData(): ?AvataxConfigurationSenderDataInterface
    {
        return $this->senderData;
    }

    /**
     * {@inheritdoc}
     */
    public function setSenderData(?AvataxConfigurationSenderDataInterface $senderData): void
    {
        $this->senderData = $senderData;
    }
}
