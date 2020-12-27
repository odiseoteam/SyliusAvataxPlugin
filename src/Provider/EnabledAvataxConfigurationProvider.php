<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Provider;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Odiseo\SyliusAvataxPlugin\Repository\AvataxConfigurationRepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class EnabledAvataxConfigurationProvider implements EnabledAvataxConfigurationProviderInterface
{
    /** @var AvataxConfigurationRepositoryInterface */
    private $avataxConfigurationRepository;

    public function __construct(AvataxConfigurationRepositoryInterface $avataxConfigurationRepository)
    {
        $this->avataxConfigurationRepository = $avataxConfigurationRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): AvataxConfigurationInterface
    {
        $configuration = $this->avataxConfigurationRepository->findOneByEnabled();
        if (!$configuration instanceof AvataxConfigurationInterface) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', get_class($configuration)));
        }

        return $configuration;
    }
}
