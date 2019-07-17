<?php

declare(strict_types=1);

namespace spec\PedroTroller\Healthcheck\Checker;

use Elasticsearch\Client;
use PedroTroller\Healthcheck\Checker;
use PhpSpec\ObjectBehavior;

class ElasticsearchSpec extends ObjectBehavior
{
    function let(Client $elasticsearch)
    {
        $this->beConstructedWith($elasticsearch);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Checker\Elasticsearch::class);
        $this->shouldImplement(Checker::class);
    }

    function it_is_healthy_if_the_request_succeed($elasticsearch)
    {
        $elasticsearch->ping()->willReturn(true);

        $this->check()->shouldReturn(Checker::STATUS_HEALTHY);
    }

    function it_is_unhealthy_if_the_request_fails($elasticsearch)
    {
        $elasticsearch->ping()->willReturn(false);

        $this->check()->shouldReturn(Checker::STATUS_UNHEALTHY);
    }

    function it_is_excluded_if_there_is_no_elasticsearch_available()
    {
        $this->beConstructedWith(null);

        $this->check()->shouldReturn(Checker::STATUS_EXCLUDED);
    }
}
