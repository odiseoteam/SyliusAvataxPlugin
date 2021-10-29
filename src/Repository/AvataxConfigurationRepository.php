<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Repository;

use Odiseo\SyliusAvataxPlugin\Entity\AvataxConfigurationInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;

class AvataxConfigurationRepository extends EntityRepository implements AvataxConfigurationRepositoryInterface
{
    public function findOneByEnabled(): ?AvataxConfigurationInterface
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.enabled = :enabled')
            ->setParameter('enabled', true)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
