<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Clear cache.
 */
class ClearCache extends Command
{
    protected function configure(): void
    {
        $this->setName('clear:cache');
        $this->setDescription('Clear cache');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (storage(config('storage.cache'))->deleteDir()) {
            $output->writeln('<bg=blue;options=bold> INFO </> Cache has been cleared.');
        } else {
            $output->writeln('<bg=red;options=bold> ERROR </> Failed to clear cache.');
        }

        return Command::SUCCESS;
    }
}
