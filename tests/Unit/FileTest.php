<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit;

use Core\Support\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    public function test_can_generate_image(): void
    {
        $filename = storage(config('storage.tmp'))->file('test.jpg');

        File::generateImage($filename, 100, 100);

        $this->assertFileExists($filename);

        storage(config('storage.tmp'))->deleteFile('test.jpg');
    }

    public function test_can_generate_pdf(): void
    {
        $filename = storage(config('storage.tmp'))->file('test.pdf');

        File::generatePDF($filename);

        $this->assertFileExists($filename);

        storage(config('storage.tmp'))->deleteFile('test.pdf');
    }
}
