<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('odiseo_sylius_avatax_plugin');

        $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
