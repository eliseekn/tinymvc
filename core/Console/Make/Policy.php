<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create model policy.
 */
class Policy extends Command
{
    protected function configure(): void
    {
        $this->setName('make:policy');
        $this->setDescription('Create model policy');
        $this->addArgument('policy', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of policy (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $policies = $input->getArgument('policy');

        foreach ($policies as $policy) {

            [, $class] = Maker::generateClass($policy, singular: true, force_singular: true);

            if (! $this->createPolicy($policy)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create policy <options=bold>'.Maker::fixPlural($class.'Policy', true).'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Policy <options=bold>'.Maker::fixPlural($class.'Policy', true).'</> has been created.');
            }

        }

        return Command::SUCCESS;
    }

    public function createPolicy(string $policy): bool
    {
        [, $class] = Maker::generateClass($policy, singular: true, force_singular: true);
        $className = Maker::fixPlural($class.'Policy', true);

        $data = Maker::stubs()->readFile('Policy.stub');
        $data = str_replace('CLASS_NAME', $className, $data);

        $storage = storage(config('storage.policies'));

        return $storage->writeFile($className.'.php', $data);
    }
}
