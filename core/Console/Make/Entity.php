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
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

/**
 * Create new entity file with its repository.
 */
class Entity extends Command
{
    public const array TYPES = [
        'string' => 'string',
        'int' => 'int',
        'float' => 'float',
        'bool' => 'bool',
        'date' => 'Carbon',
    ];

    protected function configure(): void
    {
        $this->setName('make:entity');
        $this->setDescription('Create new entity');
        $this->addArgument('entity', InputArgument::REQUIRED | InputArgument::IS_ARRAY, 'The name of entity (separated by space if many)');
        $this->addOption('fields', null, InputOption::VALUE_OPTIONAL, 'Specify fields (e.g: --fields="name:string, price:float, published_at:date")');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $entities = $input->getArgument('entity');
        $fields = $this->getFields($input, $output);

        foreach ($entities as $entity) {
            [, $class] = Maker::generateClass($entity);
            $class = Maker::fixPlural($class, true);

            if (! $this->createEntity($entity, $fields)) {
                $output->writeln('<bg=red;options=bold> ERROR </>  Failed to create entity <options=bold>'.$class.'</>.');

                continue;
            }

            $output->writeln('<bg=blue;options=bold> INFO </> Entity <options=bold>'.$class.'</> has been created.');
        }

        return Command::SUCCESS;
    }

    public function createEntity(string $entity, array $fields = []): bool
    {
        [$name, $class] = Maker::generateClass($entity);
        [, $modelClass] = Maker::generateClass($entity, 'model', true, true);

        $data = Maker::stubs()->addPath('database')->readFile('Entity.stub');
        $data = Maker::addNamespace($data, 'App\Database\Entities');
        $data = str_replace('CLASSNAME', Maker::fixPlural($class, true), $data);
        $data = str_replace('TABLE_NAME', $name, $data);
        $data = str_replace('MODEL_NAME', $modelClass, $data);
        $data = str_replace('    // PROPERTIES'.PHP_EOL, $this->generateProperties($fields), $data);
        $data = str_replace('    // METHODS'.PHP_EOL, $this->generateMethods($fields), $data);

        return storage(config('storage.entities'))
            ->writeFile(Maker::fixPlural($class, true).'.php', $data);
    }

    protected function getFields(InputInterface $input, OutputInterface $output): array
    {
        $option = $input->getOption('fields');

        if (! empty($option)) {
            return $this->parseFields($option);
        }

        if (! $input->isInteractive()) {
            return [];
        }

        $fields = [];

        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        while (true) {
            $field = $helper->ask($input, $output, new Question('New property name (press <return> to stop adding fields): '));

            if (empty($field)) {
                break;
            }

            $type = $helper->ask($input, $output, new ChoiceQuestion('Field type [string]: ', array_keys(self::TYPES), 'string'));
            $fields[$field] = self::TYPES[$type];
        }

        return $fields;
    }

    protected function parseFields(string $option): array
    {
        $fields = [];

        foreach (explode(',', $option) as $field) {
            if (empty(trim($field))) {
                continue;
            }

            [$name, $type] = array_pad(explode(':', trim($field)), 2, 'string');
            $fields[trim($name)] = self::TYPES[strtolower(trim($type))] ?? 'string';
        }

        return $fields;
    }

    protected function generateProperties(array $fields): string
    {
        $properties = '';

        foreach ($fields as $field => $type) {
            $properties .= '    protected ?'.$type.' $'.self::camelCase($field).' = null;'.PHP_EOL.PHP_EOL;
        }

        return $properties;
    }

    protected function generateMethods(array $fields): string
    {
        $methods = '';

        foreach ($fields as $field => $type) {
            $property = self::camelCase($field);
            $method = ucfirst($property);

            $methods .= PHP_EOL;
            $methods .= '    public function get'.$method.'(): ?'.$type.PHP_EOL;
            $methods .= '    {'.PHP_EOL;
            $methods .= '        return $this->'.$property.';'.PHP_EOL;
            $methods .= '    }'.PHP_EOL.PHP_EOL;
            $methods .= '    public function set'.$method.'(?'.$type.' $'.$property.'): self'.PHP_EOL;
            $methods .= '    {'.PHP_EOL;
            $methods .= '        $this->'.$property.' = $'.$property.';'.PHP_EOL.PHP_EOL;
            $methods .= '        return $this;'.PHP_EOL;
            $methods .= '    }'.PHP_EOL;
        }

        return $methods;
    }

    protected static function camelCase(string $field): string
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $field))));
    }
}
