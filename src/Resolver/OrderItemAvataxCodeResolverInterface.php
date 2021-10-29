<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Resolver;

use Sylius\Component\Core\Model\OrderItemInterface;

interface OrderItemAvataxCodeResolverInterface
{
    public function getTaxCode(OrderItemInterface $item): string;
}
