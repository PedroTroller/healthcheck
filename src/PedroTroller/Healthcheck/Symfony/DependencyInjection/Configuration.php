<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @var string
     */
    private const CONFIG_NAME = 'healthcheck';

    public function getConfigTreeBuilder()
    {
        $builder  = new TreeBuilder(self::CONFIG_NAME);
        $rootNode = method_exists($builder, 'getRootNode')
            ? $builder->getRootNode()
            : $builder->root(self::CONFIG_NAME)
        ;

        $rootNode
            ->children()
            ->booleanNode('detailed')->defaultValue(false)->end()
            ->end()
        ;

        return $builder;
    }
}
