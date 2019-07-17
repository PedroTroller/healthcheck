<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck;

interface Checker
{
    public const STATUS_HEALTHY = 1;

    public const STATUS_UNHEALTHY = -1;

    public const STATUS_EXCLUDED = 0;

    public function check(): int;

    public function getName(): string;
}
