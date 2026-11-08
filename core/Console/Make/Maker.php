<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Console\Make;

use Core\Support\Storage;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

/**
 * Helper that generates stubs files.
 */
class Maker
{
    private static ?InputInterface $input = null;

    private static ?OutputInterface $output = null;

    private static bool $skipped = false;

    /**
     * Set console input/output used to prompt before overwriting files.
     */
    public static function setIo(?InputInterface $input, ?OutputInterface $output): void
    {
        self::$input = $input;
        self::$output = $output;
    }

    /**
     * Write generated file, asking confirmation if it already exists.
     * Without an interactive console, existing files are overwritten.
     */
    public static function writeFile(Storage $storage, string $filename, string $data): bool
    {
        self::$skipped = false;

        if ($storage->isFile($filename) && ! self::confirmOverwrite($storage->getPath().$filename)) {
            self::$skipped = true;

            return false;
        }

        return $storage->writeFile($filename, $data);
    }

    /**
     * Print an error message, unless the file was skipped by the user.
     */
    public static function error(OutputInterface $output, string $message): void
    {
        if (self::$skipped) {
            $output->writeln('<bg=yellow;options=bold> SKIPPED </> Existing file was left unchanged.');
            self::$skipped = false;

            return;
        }

        $output->writeln('<bg=red;options=bold> ERROR </> '.$message);
    }

    private static function confirmOverwrite(string $path): bool
    {
        if (is_null(self::$input) || is_null(self::$output) || ! self::$input->isInteractive()) {
            return true;
        }

        $relative = str_starts_with($path, APP_ROOT) ? substr($path, strlen(APP_ROOT)) : $path;

        return (new QuestionHelper)->ask(
            self::$input,
            self::$output,
            new ConfirmationQuestion('<bg=yellow;options=bold> WARNING </> File <options=bold>'.$relative.'</> already exists. Overwrite? (yes/no) [<fg=red>no</>] ', false)
        );
    }

    /**
     * Get stubs storage path.
     */
    public static function stubs(): Storage
    {
        return storage(config('storage.stubs'));
    }

    public static function removeUnderscore(string $word): string
    {
        if (str_contains($word, '_')) {
            $words = explode('_', $word);
            $word = '';

            foreach ($words as $w) {
                $word .= ucfirst($w);
            }
        }

        return $word;
    }

    public static function addNamespace(string $data, string $base, ?string $namespace = null): string
    {
        return is_null($namespace)
            ? str_replace('NAMESPACE', $base, $data)
            : str_replace('NAMESPACE', "$base\\$namespace", $data);
    }

    public static function fixPlural(string $word, bool $remove = false): string
    {
        if (! $remove) {
            if ($word[-1] === 'y') {
                $word = substr($word, 0, -1).'ies';
            }

            if ($word[-1] !== 's') {
                $word .= 's';
            }
        } else {
            if (str_ends_with($word, 'ies')) {
                $word = substr($word, 0, -3).'y';
            } elseif ($word[-1] === 's') {
                $word = substr($word, 0, -1);
            }
        }

        return $word;
    }

    public static function generateClass(string $base_name, string $suffix = '', bool $singular = false, bool $force_singular = false): array
    {
        $name = ucfirst(strtolower($base_name));

        if (! $singular) {
            $name = self::fixPlural($name);
        }

        if ($force_singular) {
            $name = self::fixPlural($name, true);
        }

        $name = self::removeUnderscore($name);

        if ($suffix === 'migration') {
            return [lcfirst($name), 'Version'.date('YmdHis')];
        }

        $suffix = self::removeUnderscore($suffix);
        $class = $name.ucfirst($suffix);

        return [lcfirst($name), $class];
    }
}
