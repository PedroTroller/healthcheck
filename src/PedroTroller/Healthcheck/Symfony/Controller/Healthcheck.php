<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\Controller;

use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Checkers;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\ServiceUnavailableHttpException;

final class Healthcheck
{
    /**
     * @var Checkers
     */
    private $checkers;

    /**
     * @var bool
     */
    private $detailed;

    public function __construct(Checkers $checkers, bool $detailed = false)
    {
        $this->checkers = $checkers;
        $this->detailed = $detailed;
    }

    public function __invoke(Request $request): Response
    {
        $healthy  = true;
        $result   = [];
        $statuses = [
            Checker::STATUS_EXCLUDED  => 'excluded',
            Checker::STATUS_HEALTHY   => 'healthy',
            Checker::STATUS_UNHEALTHY => 'unhealthy',
        ];

        foreach ($this->checkers->waiting(1) as $checker) {
            $name   = $checker->getName();
            $status = $checker->check();

            if (Checker::STATUS_EXCLUDED !== $status) {
                $result[$name] = $statuses[$status];
            }

            if (Checker::STATUS_UNHEALTHY === $status) {
                $healthy = false;
            }
        }

        if (true === $healthy) {
            return $this->detailed
                ? new JsonResponse($result, Response::HTTP_OK)
                : new Response('', Response::HTTP_OK)
            ;
        }

        throw new ServiceUnavailableHttpException(null, (string) json_encode($result));
    }
}
