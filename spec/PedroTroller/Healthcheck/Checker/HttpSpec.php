<?php

declare(strict_types=1);

namespace spec\PedroTroller\Healthcheck\Checker;

use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;
use PhpSpec\ObjectBehavior;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HttpSpec extends ObjectBehavior
{
    function let(ClientInterface $client, RequestInterface $request)
    {
        $this->beConstructedWith($client, $request, new Logger(null), 200);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Checker\Http::class);
        $this->shouldImplement(Checker::class);
    }

    function it_can_check_if_request_succeed($client, $request, ResponseInterface $response)
    {
        $client->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(200);

        $this->check()->shouldReturn(Checker::STATUS_HEALTHY);
    }

    function it_can_check_if_request_fails($client, $request, ResponseInterface $response)
    {
        $client->sendRequest($request)->willReturn($response);
        $response->getStatusCode()->willReturn(501);

        $this->check()->shouldReturn(Checker::STATUS_UNHEALTHY);
    }
}
