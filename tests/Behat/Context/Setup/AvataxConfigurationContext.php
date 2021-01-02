<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use Doctrine\ORM\EntityManagerInterface;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Odiseo\SyliusAvataxPlugin\Repository\AvataxConfigurationRepositoryInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class AvataxConfigurationContext implements Context
{
    /** @var SharedStorageInterface */
    private $sharedStorage;

    /** @var FactoryInterface */
    private $avataxConfigurationFactory;

    /** @var AvataxConfigurationRepositoryInterface */
    private $avataxConfigurationRepository;

    /** @var RepositoryInterface */
    private $zoneRepository;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        FactoryInterface $avataxConfigurationFactory,
        AvataxConfigurationRepositoryInterface $avataxConfigurationRepository,
        RepositoryInterface $zoneRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->avataxConfigurationFactory = $avataxConfigurationFactory;
        $this->avataxConfigurationRepository = $avataxConfigurationRepository;
        $this->zoneRepository = $zoneRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $appName
     * @Given there is an existing avatax configuration with :name app name
     */
    public function thereIsAConfigurationWithName(string $appName): void
    {
        $configuration = $this->createConfiguration($appName);

        $this->saveConfiguration($configuration);
    }

    /**
     * @param string $appName
     * @return AvataxConfigurationInterface
     */
    private function createConfiguration(string $appName): AvataxConfigurationInterface
    {
        /** @var ZoneInterface $zone */
        $zone = $this->zoneRepository->findOneBy([
            'code' => 'europe'
        ]);

        /** @var AvataxConfigurationInterface $configuration */
        $configuration = $this->avataxConfigurationFactory->createNew();

        $configuration->setAppName($appName);
        $configuration->setAppVersion('1.0');
        $configuration->setEnabled(true);
        $configuration->setSandbox(true);
        $configuration->setAccountId(123456);
        $configuration->setLicenseKey('123456');
        $configuration->setZone($zone);

        return $configuration;
    }

    /**
     * @param AvataxConfigurationInterface $configuration
     */
    private function saveConfiguration(AvataxConfigurationInterface $configuration): void
    {
        $this->avataxConfigurationRepository->add($configuration);
    }
}
