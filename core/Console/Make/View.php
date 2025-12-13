<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new view template.
 */
class View extends Command
{
    protected function configure(): void
    {
        $this->setName('make:view');
        $this->setDescription('Create new view template');
        $this->addArgument('view', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of view or layout (separated by space if many)');
        $this->addOption('extends', null, InputOption::VALUE_REQUIRED, 'Extends from layout');
        $this->addOption('path', null, InputOption::VALUE_OPTIONAL, 'Specify subdirectory path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $views = $input->getArgument('view');

        if (is_null($input->getOption('extends'))) {
            foreach ($views as $view) {
                if (! $this->createView(null, $view, $input->getOption('path'))) {
                    $output->writeln('<bg=red;options=bold> ERROR </> Failed to create view layout <options=bold>'.$view.'</>.');
                } else {
                    $output->writeln('<bg=blue;options=bold> INFO </> View layout <options=bold>'.$view.'</> has been created.');
                }
            }

            return Command::SUCCESS;
        }

        foreach ($views as $view) {
            if (! $this->createView($view, $input->getOption('extends'), $input->getOption('path'))) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create view template <options=bold>'.$view.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> View template <options=bold>'.$view.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createView(?string $view, ?string $layout, ?string $path = null): bool
    {
        $data = is_null($view) && ! is_null($layout)
            ? Maker::stubs()->addPath('views')->readFile('layout.stub')
            : Maker::stubs()->addPath('views')->readFile('blank.stub');

        $data = str_replace('LAYOUT_NAME', '{% extends "layouts/'.$layout.'.html.twig" %}', $data);
        $data = is_null($view) ? $data : str_replace('RESOURCE_NAME', $view, $data);

        $storage = storage(config('storage.views'));

        $storage = is_null($view) && ! is_null($layout)
            ? $storage->addPath('layouts')
            : $storage->addPath($path ?? '');

        $view = is_null($view) && ! is_null($layout) ? $layout : $view;

        return $storage->writeFile($view.'.html.twig', $data);
    }
}
