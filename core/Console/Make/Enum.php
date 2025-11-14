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
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Create new custom enum.
 */
class Enum extends Command
{
    protected static $defaultName = 'make:enum';

    protected function configure(): void
    {
        $this->setDescription('Create new enum');
        $this->addArgument('enum', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of enum (separated by space if many)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $enums = $input->getArgument('enum');

        foreach ($enums as $enum) {
            [, $class] = Maker::generateClass($enum, singular: true);

            if (! $this->createEnum($enum)) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create enum <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Enum <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public static function createEnum(string $enum): bool
    {
        [, $class] = Maker::generateClass($enum, singular: true);

        $data = Maker::stubs()->readFile('Enum.stub');
        $data = str_replace('ENUM_NAME', $class, $data);

        return storage(config('storage.enums'))->writeFile($class.'.php', $data);
    }
}
