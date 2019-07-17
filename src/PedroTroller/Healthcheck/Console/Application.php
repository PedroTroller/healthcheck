<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Console;

use PedroTroller\Healthcheck\Checker\Doctrine;
use PedroTroller\Healthcheck\Checker\Elasticsearch;
use PedroTroller\Healthcheck\Checker\PhpRedis;
use PedroTroller\Healthcheck\Checkers;
use PedroTroller\Healthcheck\Logger;
use PedroTroller\Healthcheck\Symfony\Command\Healthcheck;
use Symfony;

final class Application extends Symfony\Component\Console\Application
{
    public function __construct()
    {
        parent::__construct('healthcheck');

        $logger = new Logger(null);

        $this->add(
            new Healthcheck(
                new Checkers(
                    [
                        new Doctrine(null, $logger),
                        new Elasticsearch(null, $logger),
                        new PhpRedis(null, $logger),
                    ]
                )
            )
        );
        $this->setDefaultCommand('healthcheck', true);
    }
}
