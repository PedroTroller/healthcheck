<?php

declare(strict_types=1);

namespace spec\PedroTroller\Healthcheck\Checker;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ConnectionException;
use Doctrine\DBAL\Statement;
use PedroTroller\Healthcheck\Checker;
use PhpSpec\ObjectBehavior;

class DoctrineSpec extends ObjectBehavior
{
    function let(Connection $connection)
    {
        $this->beConstructedWith($connection);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Checker\Doctrine::class);
        $this->shouldImplement(Checker::class);
    }

    function it_is_healthy_if_the_request_succeed($connection, Statement $statement)
    {
        $connection->prepare('SHOW STATUS')->willReturn($statement);
        $statement->execute()->shouldBeCalledTimes(1)->willReturn(true);

        $this->check()->shouldReturn(Checker::STATUS_HEALTHY);
    }

    function it_is_unhealthy_if_the_request_fails($connection, Statement $statement)
    {
        $connection->prepare('SHOW STATUS')->willReturn($statement);
        $statement->execute()->willThrow(new ConnectionException());

        $this->check()->shouldReturn(Checker::STATUS_UNHEALTHY);
    }

    function it_is_excluded_if_there_is_no_doctrine_available()
    {
        $this->beConstructedWith(null);

        $this->check()->shouldReturn(Checker::STATUS_EXCLUDED);
    }
}
