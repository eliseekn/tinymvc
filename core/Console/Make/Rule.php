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
 * Create new request rule.
 */
class Rule extends Command
{
    protected function configure(): void
    {
        $this->setName('make:rule');
        $this->setDescription('Create new custom validator rule');
        $this->addArgument('rule', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of rule (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $rules = $input->getArgument('rule');

        foreach ($rules as $rule) {
            [, $class] = Maker::generateClass($rule, singular: true);

            if (! $this->createRule($rule)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create request rule <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Request rule <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createRule(string $rule): bool
    {
        [$name, $class] = Maker::generateClass($rule, singular: true);

        $data = Maker::stubs()->addPath('validators')->readFile('Rule.stub');
        $data = Maker::addNamespace($data, 'App\Http\Validation\Rules');
        $data = str_replace('CLASS_NAME', $class, $data);
        $data = str_replace('RULE_NAME', strtolower($name), $data);

        $storage = storage(config('storage.rules'));

        return $storage->writeFile($class.'.php', $data);
    }
}
