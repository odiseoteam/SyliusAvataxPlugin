<?php

declare(strict_types=1);

namespace Tests\Odiseo\SyliusAvataxPlugin\Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

/**
 * @ORM\Table(name="sylius_product")
 * @ORM\Entity
 */
class Product extends BaseProduct implements AvataxAwareInterface
{
    use AvataxTrait;
}
