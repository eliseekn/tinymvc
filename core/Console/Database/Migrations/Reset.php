<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Database\Migrations;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Reset migrations.
 */
class Reset extends Command
{
    protected function configure(): void
    {
        $this->setName('migrations:reset');
        $this->setDescription('Reset migrations');
        $this->addArgument('migration', InputArgument::OPTIONAL | InputArgument::IS_ARRAY, 'The name of migrations (separated by space if many)');
        $this->addOption('seed', null, InputOption::VALUE_NONE, 'Insert all seeds');
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $migrations = $input->getArgument('migration');

        $this->getApplication()->find('migrations:delete')->run(new ArrayInput($migrations), $output);
        $this->getApplication()->find('migrations:run')->run(new ArrayInput($migrations), $output);

        if ($input->getOption('seed')) {
            $this->getApplication()->find('db:seed')->run(new ArrayInput($migrations), $output);
        }

        return Command::SUCCESS;
    }
}
