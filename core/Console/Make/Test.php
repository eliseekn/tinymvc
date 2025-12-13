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
 * Create new test.
 */
class Test extends Command
{
    protected function configure(): void
    {
        $this->setName('make:test');
        $this->setDescription('Create new test case');
        $this->addArgument('test', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of test (separated by space if many)');
        $this->addOption('unit', 'u', InputOption::VALUE_NONE, 'Setup for unit test');
        $this->addOption('browser', 'b', InputOption::VALUE_NONE, 'Setup for browser test');
        $this->addOption('namespace', null, InputOption::VALUE_OPTIONAL, 'Specify namespace');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tests = $input->getArgument('test');

        foreach ($tests as $test) {
            [, $class] = Maker::generateClass($test, 'test', true);

            if ($input->getOption('unit')) {
                $result = $this->createUnitTest($test, $input->getOption('namespace'));
            } elseif ($input->getOption('browser')) {
                $result = $this->createBrowserTest($test, $input->getOption('namespace'));
            } else {
                $result = $this->createTest($test, $input->getOption('namespace'));
            }

            if (! $result) {
                $output->writeln('<bg=red;options=bold> ERROR </> Failed to create test <options=bold>'.$class.'</>.');
            } else {
                $output->writeln('<bg=blue;options=bold> INFO </> Test <options=bold>'.$class.'</> has been created.');
            }
        }

        return Command::SUCCESS;
    }

    public function createTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($test, 'test', true);

        $data = Maker::stubs()->addPath('tests')->readFile('FeatureTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = Maker::addNamespace($data, 'Tests\Feature', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Feature')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }

    public function createUnitTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($test, 'test', true);

        $data = Maker::stubs()->addPath('tests')->readFile('UnitTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = Maker::addNamespace($data, 'Tests\Unit', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Unit')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }

    public function createBrowserTest(string $test, ?string $namespace = null): bool
    {
        [, $class] = Maker::generateClass($test, 'test', true);

        $data = Maker::stubs()->addPath('tests')->readFile('BrowserTest.stub');
        $data = str_replace('CLASSNAME', $class, $data);
        $data = Maker::addNamespace($data, 'Tests\Browser', $namespace);

        return storage(config('storage.tests'))
            ->addPath('Browser')->addPath($namespace ?? '')
            ->writeFile($class.'.php', $data);
    }
}
