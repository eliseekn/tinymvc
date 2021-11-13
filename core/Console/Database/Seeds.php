<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Console\Database;

use App\Database\Seeds\Seeder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Insert seeds 
 */
class Seeds extends Command
{
    protected static $defaultName = 'db:seed';

    protected function configure()
    {
        $this->setDescription('Insert seeds');
        $this->addArgument('seed', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of seeds (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (config('app.env') === 'test') {
            $output->writeln('<fg=yellow>WARNING: You are running seeds on APP_ENV=test</>');
        }

        $seeds = $input->getArgument('seed');

        if (empty($seeds)) {
            Seeder::run();
            $output->writeln('<info>All seeds have been inserted</info>');
            
            return Command::SUCCESS;
        }
        
        foreach ($seeds as $seed) {
            $this->seed($output, $seed);
        }

        return Command::SUCCESS;
    }

    protected function seed(OutputInterface $output, string $seed)
    {
        $seed = '\App\Database\Seeds\\' . $seed;
        $seed::insert();
        $output->writeln('<info>Seed "' . $seed . '" has been inserted</info>');
    }
}
