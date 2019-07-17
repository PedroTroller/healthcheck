<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Checker;

use Doctrine\DBAL\Connection;
use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;
use Throwable;

final class Doctrine implements Checker
{
    /**
     * @var Connection | null
     */
    private $connection;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(?Connection $connection, Logger $logger)
    {
        $this->connection = $connection;
        $this->logger     = $logger;
    }

    public function check(): int
    {
        if (null === $this->connection) {
            return self::STATUS_EXCLUDED;
        }

        try {
            $this->connection->prepare('SELECT 1')->execute();

            $this->logger->info("Checker {$this->getName()} is healthy.");

            return self::STATUS_HEALTHY;
        } catch (Throwable $throwable) {
            $this->logger->warning("Checker {$this->getName()} is unhealthy.", ['error' => $throwable]);

            return self::STATUS_UNHEALTHY;
        }
    }

    public function getName(): string
    {
        return 'doctrine';
    }
}
