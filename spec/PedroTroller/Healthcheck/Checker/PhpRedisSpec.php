<?php

declare(strict_types=1);

namespace spec\PedroTroller\Healthcheck\Checker;

use Exception;
use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Logger;
use PhpSpec\ObjectBehavior;
use Predis\Client;

class PhpRedisSpec extends ObjectBehavior
{
    function let(Client $predis)
    {
        $this->beConstructedWith($predis, new Logger(null));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Checker\PhpRedis::class);
        $this->shouldImplement(Checker::class);
    }

    function it_is_healthy_if_the_request_succeed($predis)
    {
        $predis->ping()->willReturn('string');

        $this->check()->shouldReturn(Checker::STATUS_HEALTHY);
    }

    function it_is_unhealthy_if_the_request_fails($predis)
    {
        $predis->ping()->willThrow(new Exception());

        $this->check()->shouldReturn(Checker::STATUS_UNHEALTHY);
    }

    function it_is_excluded_if_there_is_no_predis_available()
    {
        $this->beConstructedWith(null, new Logger(null));

        $this->check()->shouldReturn(Checker::STATUS_EXCLUDED);
    }
}
