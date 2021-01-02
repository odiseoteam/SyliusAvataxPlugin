<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Webmozart\Assert\Assert;

final class AvataxConfigurationContext implements Context
{
    /** @var RepositoryInterface */
    private $avataxConfigurationRepository;

    public function __construct(
        RepositoryInterface $avataxConfigurationRepository
    ) {
        $this->avataxConfigurationRepository = $avataxConfigurationRepository;
    }

    /**
     * @Transform /^avatax configuration "([^"]+)"$/
     * @Transform /^"([^"]+)" avatax configuration/
     * @param string $appName
     * @return AvataxConfigurationInterface
     */
    public function getConfigurationByName(string $appName): AvataxConfigurationInterface
    {
        /** @var AvataxConfigurationInterface $configuration */
        $configuration = $this->avataxConfigurationRepository->findOneBy(['appName' => $appName]);

        Assert::notNull(
            $configuration,
            'Avatax configuration with app name %s does not exist'
        );

        return $configuration;
    }
}
