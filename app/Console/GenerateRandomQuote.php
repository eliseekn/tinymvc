<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Console;

use Core\Http\Client\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateRandomQuote extends Command
{
    protected function configure(): void
    {
        $this->setName('misc:random-quote');
        $this->setDescription('Generate ramdom quote');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $response = Client::get('https://zenquotes.io/api/random')->getBodyAsJson();

        $output->writeln(sprintf('"%s" %s', '<fg=blue;options=bold>'.$response[0]['q'].'</>', '<fg=white;options=bold>'.$response[0]['a'].'</>'));

        return Command::SUCCESS;
    }
}
