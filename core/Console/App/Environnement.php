<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\App;

use Core\Enums\AppEnv;
use Core\Support\Config;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Define application environnement.
 */
class Environnement extends Command
{
    protected function configure(): void
    {
        $this->setName('app:env');
        $this->setDescription('Define application environnement');
        $this->addArgument('name', InputArgument::REQUIRED, 'Specify application environnement (test, local or prod)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (! in_array($input->getArgument('name'), [AppEnv::TEST, AppEnv::PROD, AppEnv::LOCAL])) {
            $output->writeln('<bg=red;options=bold> ERROR </> Invalid application environnement name.');

            return Command::FAILURE;
        }

        Config::updateEnv(['APP_ENV' => $input->getArgument('name').PHP_EOL]);

        $output->writeln('<bg=blue;options=bold> INFO </> Application environnement has been defined to <options=bold>'.$input->getArgument('name').'</>.');

        return Command::SUCCESS;
    }
}
