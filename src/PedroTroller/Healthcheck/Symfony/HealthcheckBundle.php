<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony;

use PedroTroller\Healthcheck\Symfony\DependencyInjection\HealthcheckExtension;
use Symfony;

final class HealthcheckBundle extends Symfony\Component\HttpKernel\Bundle\Bundle
{
    public function getContainerExtension()
    {
        return new HealthcheckExtension();
    }
}
