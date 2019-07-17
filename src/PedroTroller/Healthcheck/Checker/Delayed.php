<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Checker;

use PedroTroller\Healthcheck\Checker;

final class Delayed implements Checker
{
    /**
     * @var Checker
     */
    private $checker;

    /**
     * @var int
     */
    private $seconds;

    /**
     * @var int
     */
    private $milliSecondsInterval;

    public function __construct(Checker $checker, int $seconds, int $milliSecondsInterval = 100)
    {
        $this->checker              = $checker;
        $this->seconds              = $seconds;
        $this->milliSecondsInterval = $milliSecondsInterval;
    }

    public function check(): int
    {
        $start = time();

        do {
            $status = $this->checker->check();

            switch ($status) {
                case Checker::STATUS_HEALTHY:
                    return Checker::STATUS_HEALTHY;
                case Checker::STATUS_EXCLUDED:
                    return Checker::STATUS_EXCLUDED;
                case Checker::STATUS_UNHEALTHY:
                    usleep($this->milliSecondsInterval * 1000);

                    break;
            }
        } while (time() - $start < $this->seconds);

        return Checker::STATUS_UNHEALTHY;
    }

    public function getName(): string
    {
        return $this->checker->getName();
    }
}
