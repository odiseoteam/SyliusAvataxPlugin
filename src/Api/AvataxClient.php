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

        $appName = $avataxConfiguration->getAppName();
        $appVersion = $avataxConfiguration->getAppVersion();
        $machineName = $avataxConfiguration->getMachineName();
        $environment = $avataxConfiguration->isSandbox() ? 'sandbox' : 'production';

        parent::__construct($appName, $appVersion, $machineName, $environment);

        $accountId = $avataxConfiguration->getAccountId();
        $licenseKey = $avataxConfiguration->getLicenseKey();

        $this->withLicenseKey($accountId, $licenseKey);
    }
}