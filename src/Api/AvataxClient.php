<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Api;

use Avalara\AvaTaxClient as BaseAvataxClient;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Odiseo\SyliusAvataxPlugin\Provider\EnabledAvataxConfigurationProviderInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AvataxClient extends BaseAvataxClient
{
    public function __construct(
        private EnabledAvataxConfigurationProviderInterface $enabledAvataxConfigurationProvider,
    ) {
        $avataxConfiguration = $this->enabledAvataxConfigurationProvider->getConfiguration();
        if (!$avataxConfiguration instanceof AvataxConfigurationInterface) {
            throw new NotFoundHttpException(
                sprintf('The "%s" has not been found', AvataxConfigurationInterface::class),
            );
        }

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
