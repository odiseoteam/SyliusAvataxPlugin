<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Api;

use Avalara\AvaTaxClient as BaseAvataxClient;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Odiseo\SyliusAvataxPlugin\Repository\AvataxConfigurationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AvataxClient extends BaseAvataxClient
{
    /** @var AvataxConfigurationRepositoryInterface */
    private $avataxConfigurationRepository;

    /** @var AvataxConfigurationInterface */
    private $avataxConfiguration;

    public function __construct(AvataxConfigurationRepositoryInterface $avataxConfigurationRepository)
    {
        $this->avataxConfigurationRepository = $avataxConfigurationRepository;

        $avataxConfiguration = $this->avataxConfigurationRepository->findOneByEnabled();
        if (!$avataxConfiguration instanceof AvataxConfigurationInterface) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', get_class($avataxConfiguration)));
        }

        $this->avataxConfiguration = $avataxConfiguration;

        $appName = $avataxConfiguration->getAppName();
        $appVersion = $avataxConfiguration->getAppVersion();
        $machineName = $avataxConfiguration->getMachineName();
        $environment = $avataxConfiguration->isSandbox() ? 'sandbox' : 'production';

        parent::__construct($appName, $appVersion, $machineName, $environment);

        $accountId = $avataxConfiguration->getAccountId();
        $licenseKey = $avataxConfiguration->getLicenseKey();

        $this->withLicenseKey($accountId, $licenseKey);
    }

    /**
     * @return AvataxConfigurationInterface
     */
    public function getConfiguration(): AvataxConfigurationInterface
    {
        return $this->avataxConfiguration;
    }
}