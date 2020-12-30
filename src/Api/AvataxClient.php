<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Api;

use Avalara\AvaTaxClient as BaseAvataxClient;
use Odiseo\SyliusAvataxPlugin\Provider\EnabledAvataxConfigurationProviderInterface;

class AvataxClient extends BaseAvataxClient
{
    /** @var EnabledAvataxConfigurationProviderInterface */
    private $enabledAvataxConfigurationProvider;

    public function __construct(EnabledAvataxConfigurationProviderInterface $enabledAvataxConfigurationProvider)
    {
        $this->enabledAvataxConfigurationProvider = $enabledAvataxConfigurationProvider;

        $avataxConfiguration = $this->enabledAvataxConfigurationProvider->getConfiguration();

        $appName = (string) $avataxConfiguration->getAppName();
        $appVersion = (string) $avataxConfiguration->getAppVersion();
        $machineName = (string) $avataxConfiguration->getMachineName();
        $environment = $avataxConfiguration->isSandbox() === true ? 'sandbox' : 'production';

        parent::__construct($appName, $appVersion, $machineName, $environment);

        $accountId = (int) $avataxConfiguration->getAccountId();
        $licenseKey = (string) $avataxConfiguration->getLicenseKey();

        $this->withLicenseKey($accountId, $licenseKey);
    }
}