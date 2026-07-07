<?php

declare(strict_types=1);

namespace M10c\UnlockedAnalyticsBundle\Command;

use M10c\UnlockedAnalyticsBundle\ClickHouse\ClickHouseSchemaInstaller;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'unlocked-analytics:clickhouse:install-schema',
    description: 'Create the analytics_event ClickHouse table.',
)]
final class InstallClickHouseSchemaCommand extends Command
{
    public function __construct(
        private readonly ClickHouseSchemaInstaller $schemaInstaller,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->schemaInstaller->install();

        (new SymfonyStyle($input, $output))->success(\sprintf('Created/verified ClickHouse table "%s".', ClickHouseSchemaInstaller::TABLE));

        return Command::SUCCESS;
    }
}
