<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console;

use Framework\System\Storage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear twig templates cache
 */
class Cache extends Command
{
    protected static $defaultName = 'cache:clear';

    protected function configure()
    {
        $this->setDescription('Clear twig templates cache');
        $this->setHelp('This command allows you to clear twig templates cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        Storage::path(config('storage.cache'))->deleteDir();
        $output->writeln('<info>Cache has been cleared</info>');

        return Command::SUCCESS;
    }
}
