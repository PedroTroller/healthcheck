<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\DependencyInjection;

use Symfony;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class HealthcheckExtension extends Symfony\Component\DependencyInjection\Extension\Extension
{
    public function load(array $config, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.yaml');

        $configuration = new Configuration();

        $parameters = $this->processConfiguration($configuration, $config);

        foreach ($this->flatten($parameters, 'healthcheck') as $key => $value) {
            $container->setParameter($key, $value);
        }
    }

    public function getConfiguration(array $config, ContainerBuilder $container)
    {
        return new Configuration();
    }

    private function flatten(array $array, string $prefix = ''): array
    {
        $result = [];

        foreach ($array as $key => $value) {
            $key = $prefix.($prefix ? '.' : '').$key;

            if (\is_array($value)) {
                $result = array_merge($result, $this->flatten($value, $key));
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
