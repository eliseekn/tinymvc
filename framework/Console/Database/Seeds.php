<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Console\Database;

use Exception;
use Framework\System\Storage;
use App\Database\Seeds\Seeder;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Manage database seeds 
 */
class Seeds extends Command
{
    protected static $defaultName = 'db:seed';

    protected function configure()
    {
        $this->setDescription('Manage seeds');
        $this->setHelp('This command allows you to insert seeds into database');
        $this->addArgument('seed', InputArgument::OPTIONAL|InputArgument::IS_ARRAY, 'The name of seed (separated by space if many).');
        $this->addOption('run', null, InputOption::VALUE_NONE, 'Insert seeds');
        $this->addOption('list', 'l', InputOption::VALUE_NONE, 'Display the list of seeds');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $seeds = $input->getArgument('seed');

        if ($input->getOption('run')) {
            if (!empty($seeds)) {
                foreach ($seeds as $seed) {
                    $this->seed($output, $seed);
                }
                
                return Command::SUCCESS;
            }


            Seeder::run();
            $output->writeln('<info>All seeds inserted successfully</info>');
        }

        else if ($input->getOption('list')) {
            $this->listSeeds($output);
        }

        throw new Exception('Invalid command line arguments');

        return Command::SUCCESS;
    }

    protected function seed(OutputInterface $output, string $seed)
    {
        $seed = $this->getSeed($seed);
        $seed::insert();
        $output->writeln('<info>Seed "' . $seed . '" inserted successfully</info>');
    }
    
    protected function listSeeds(OutputInterface $output): void
    {
        $seeds = Storage::path(config('storage.seeds'))->getFiles();
        $rows = [];

        foreach ($seeds as $seed) {
            if ($seed === 'RoleSeed.php') {
                $rows[] = [$seed];
            }
        }

        $table = new Table($output);
        $table->setHeaders(['Seeds']);
        $table->setRows($rows);
        $table->render();
    }
    
    protected function getSeed(string $seed): string
    {
        $seed = ucfirst($seed) . 'Seed';
        return '\App\Database\Seeds\\' . $seed;
    }
}
