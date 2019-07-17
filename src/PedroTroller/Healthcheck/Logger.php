<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Throwable;

final class Logger implements LoggerInterface
{
    /**
     * @var LoggerInterface | null
     */
    private $logger;

    public function __construct(?LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function emergency($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->emergency($message, $this->serialize($context));
        }
    }

    public function alert($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->alert($message, $this->serialize($context));
        }
    }

    public function critical($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->critical($message, $this->serialize($context));
        }
    }

    public function error($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->error($message, $this->serialize($context));
        }
    }

    public function warning($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->warning($message, $this->serialize($context));
        }
    }

    public function notice($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->notice($message, $this->serialize($context));
        }
    }

    public function info($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->info($message, $this->serialize($context));
        }
    }

    public function debug($message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->debug($message, $this->serialize($context));
        }
    }

    public function log($level, $message, array $context = []): void
    {
        if (null !== $this->logger) {
            $this->logger->log($level, $message, $context);
        }
    }

    private function serialize($data)
    {
        if (\is_array($data)) {
            return array_map([$this, 'serialize'], $data);
        }

        if ($data instanceof DateTime || $data instanceof DateTimeImmutable || $data instanceof DateTimeInterface) {
            return $data->format(DateTime::RFC3339);
        }

        if ($data instanceof Throwable || $data instanceof Exception) {
            return $this->serialize([
                'message'  => $data->getMessage(),
                'code'     => $data->getCode(),
                'file'     => $data->getFile(),
                'line'     => $data->getLine(),
                'trace'    => $data->getTrace(),
                'previous' => $data->getPrevious(),
            ]);
        }

        return $data;
    }
}
