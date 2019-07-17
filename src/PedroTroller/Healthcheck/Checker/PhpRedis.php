<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Checker;

use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;
use Predis\ClientInterface;
use Throwable;

final class PhpRedis implements Checker
{
    /**
     * @var ClientInterface | null
     */
    private $predis;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(?ClientInterface $predis, Logger $logger)
    {
        $this->predis = $predis;
        $this->logger = $logger;
    }

    public function check(): int
    {
        if (null === $this->predis) {
            return self::STATUS_EXCLUDED;
        }

        try {
            $this->predis->ping();

            $this->logger->info("Checker {$this->getName()} is healthy.");

            return self::STATUS_HEALTHY;
        } catch (Throwable $throwable) {
            $this->logger->warning("Checker {$this->getName()} is unhealthy.", ['error' => $throwable]);

            return self::STATUS_UNHEALTHY;
        }
    }

    public function getName(): string
    {
        return 'predis';
    }
}
