<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Checker;

use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;

final class Http implements Checker
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var int
     */
    private $expectedStatusCode;

    /**
     * @var string
     */
    private $name;

    public function __construct(
        ClientInterface $client,
        RequestInterface $request,
        Logger $logger,
        int $expectedStatusCode = 200,
        string $name            = null
    ) {
        $this->client             = $client;
        $this->request            = $request;
        $this->logger             = $logger;
        $this->expectedStatusCode = $expectedStatusCode;
        $this->name               = $name ?: 'http';
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function check(): int
    {
        try {
            $response = $this->client->sendRequest($this->request);

            if ($this->expectedStatusCode === $response->getStatusCode()) {
                $this->logger->info(
                    "Checker {$this->getName()} is healthy."
                );

                return Checker::STATUS_HEALTHY;
            }

            $this->logger->warning(
                "Checker {$this->getName()} is unhealthy.",
                [
                    'request'            => $this->request,
                    'response'           => $response,
                    'expectedStatusCode' => $this->expectedStatusCode,
                ]
            );

            return Checker::STATUS_UNHEALTHY;
        } catch (Throwable $throwable) {
            $this->logger->warning(
                "Checker {$this->getName()} is unhealthy.",
                [
                    'error'              => $throwable,
                    'request'            => $this->request,
                    'expectedStatusCode' => $this->expectedStatusCode,
                ]
            );

            return Checker::STATUS_UNHEALTHY;
        }
    }
}
