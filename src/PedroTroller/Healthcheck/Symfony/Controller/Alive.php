<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class Alive
{
    public function __invoke(Request $request): Response
    {
        return new Response('', Response::HTTP_OK);
    }
}
