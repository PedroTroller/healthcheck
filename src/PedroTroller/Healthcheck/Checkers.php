<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck;

use Generator;
use IteratorAggregate;
use PedroTroller\Healthcheck\Checker\Delayed;

final class Checkers implements IteratorAggregate
{
    /**
     * @var iterable<Checker>
     */
    private $checkers;

    /**
     * @param iterable<Checker> $checkers
     */
    public function __construct(iterable $checkers)
    {
        $this->checkers = $checkers;
    }

    /**
     * @return Generator<Checker>
     */
    public function getIterator(): Generator
    {
        foreach ($this->checkers as $checker) {
            yield $checker;
        }
    }

    public function waiting(int $seconds, int $milliSecondsInterval = 100): self
    {
        return new self(
            array_map(
                function (Checker $checker) use ($seconds, $milliSecondsInterval): Delayed {
                    return new Delayed($checker, $seconds, $milliSecondsInterval);
                },
                iterator_to_array($this)
            )
        );
    }
}
