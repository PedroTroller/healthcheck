<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Checker;

use Elasticsearch\Client;
use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;

final class Elasticsearch implements Checker
{
    /**
     * @var Client | null
     */
    private $elasticsearch;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(?Client $elasticsearch, Logger $logger)
    {
        $this->elasticsearch = $elasticsearch;
        $this->logger        = $logger;
    }

    public function check(): int
    {
        if (null === $this->elasticsearch) {
            return self::STATUS_EXCLUDED;
        }

        if ($this->elasticsearch->ping()) {
            $this->logger->info(
                "Checker {$this->getName()} is healthy."
            );

            return self::STATUS_HEALTHY;
        }

        $this->logger->warning(
            "Checker {$this->getName()} is unhealthy."
        );

        return self::STATUS_UNHEALTHY;
    }

    public function getName(): string
    {
        return 'elasticsearch';
    }
}
