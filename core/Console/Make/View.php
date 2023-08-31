<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new view template 
 */
class View extends Command
{
    protected static $defaultName = 'make:view';

    protected function configure(): void
    {
        $this->setDescription('Create new view template');
        $this->addArgument('view', InputArgument::REQUIRED|InputArgument::IS_ARRAY, 'The name of view or layout (separated by space if many)');
        $this->addOption('extends', null, InputOption::VALUE_REQUIRED, 'Extends from layout');
        $this->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Specify subdirectory path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $views = $input->getArgument('view');

        if (is_null($input->getOption('extends'))) {
            foreach ($views as $view) {
                if (!Maker::createView(null, $view, $input->getOption('path'))) {
                    $output->writeln('<error>[ERROR] Failed to create view layout "' . $view . '"</error>');
                }
    
                $output->writeln('<info>[INFO] View layout "' . $view . '" has been created</info>');
            }

            return Command::SUCCESS;
        }

        foreach ($views as $view) {
            if (!Maker::createView($view, $input->getOption('extends'), $input->getOption('path'))) {
                $output->writeln('<error>[ERROR] Failed to create view template "' . $view . '"</error>');
            }

            $output->writeln('<info>[INFO] View template "' . $view . '" has been created</info>');
        }

        return Command::SUCCESS;
    }
}
