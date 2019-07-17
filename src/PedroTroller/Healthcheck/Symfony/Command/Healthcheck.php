<?php

declare(strict_types=1);

namespace PedroTroller\Healthcheck\Symfony\Command;

use InvalidArgumentException;
use PedroTroller\Healthcheck\Checker;
use PedroTroller\Healthcheck\Checkers;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;

final class Healthcheck extends Command
{
    /**
     * @var Checkers
     */
    private $checkers;

    public function __construct(Checkers $checkers)
    {
        $this->checkers = $checkers;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('healthcheck')
            ->addOption(
                'waiting-time',
                'w',
                InputOption::VALUE_OPTIONAL,
                'Seconds to wait for an healthy status.',
                2
            )
            ->addOption(
                'just-print',
                'j',
                InputOption::VALUE_NONE | InputOption::VALUE_OPTIONAL,
                'Just print the table result when finished.',
                null
            )
            ->addArgument(
                'checker',
                InputArgument::OPTIONAL | InputArgument::IS_ARRAY,
                'The list of checkers to execute (all if not set).',
                array_map(
                    function (Checker $checker): string {
                        return $checker->getName();
                    },
                    iterator_to_array($this->checkers)
                )
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (false === $output instanceof ConsoleOutput) {
            throw new InvalidArgumentException('$output should be an instance of Symfony\Component\Console\Output\ConsoleOutput.');
        }

        $output->writeln('');
        $result = 0;

        $waitTime     = (int) ($input->getOption('waiting-time'));
        $justPrint    = $input->hasOption('just-print');
        $checkerNames = $input->getArgument('checker');

        $table = new Table($output->section());
        $table->setHeaders(['Service', 'Status']);

        if (false === $justPrint) {
            $table->render();
        }

        foreach ($this->checkers->waiting($waitTime) as $checker) {
            if (false === \in_array($checker->getname(), $checkerNames, true)) {
                continue;
            }

            $row = [$checker->getName()];

            switch ($checker->check()) {
                case Checker::STATUS_HEALTHY:
                    $justPrint
                        ? $table->addRow([$checker->getName(), '<info>HEALTHY</info>'])
                        : $table->appendRow([$checker->getName(), '<info>HEALTHY</info>'])
                    ;

                    break;
                case Checker::STATUS_EXCLUDED:
                    if ($output->isVerbose()) {
                        $justPrint
                            ? $table->addRow([$checker->getName(), '<comment>EXCLUDED</comment>'])
                            : $table->appendRow([$checker->getName(), '<comment>EXCLUDED</comment>'])
                        ;
                    }

                    break;
                case Checker::STATUS_UNHEALTHY:
                    $result = 1;
                    $justPrint
                        ? $table->addRow([$checker->getName(), '<fg=red;options=bold>UNHEALTHY</>'])
                        : $table->appendRow([$checker->getName(), '<fg=red;options=bold>UNHEALTHY</>'])
                    ;

                    break;
            }
        }

        if ($justPrint) {
            $table->render();
        }

        $output->writeln('');

        return $result;
    }
}
