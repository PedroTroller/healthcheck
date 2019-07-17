<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $builder = new TreeBuilder();
        $builder
            ->root('healthcheck')
            ->children()
            ->booleanNode('detailed')->defaultValue(false)->end()
            ->end()
        ;

        return $builder;
    }
}
