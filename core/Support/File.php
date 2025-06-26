<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Dompdf\Dompdf;
use StanDaniels\ImageGenerator\Canvas;
use StanDaniels\ImageGenerator\Image as ImageGenerator;

/**
 * File support.
 */
abstract class File
{
    public static function generateImage(string $filename, int $width, int $height): bool
    {
        ImageGenerator::create(Canvas::create($width, $height), $filename);

        return storage(File::getDirname($filename))->isFile(DIRECTORY_SEPARATOR.File::getBasename($filename));
    }

    public static function generatePDF(string $filename): bool
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml('TinyMVC');
        $dompdf->render();
        $output = $dompdf->output();

        return storage(File::getDirname($filename))->writeFile(DIRECTORY_SEPARATOR.File::getBasename($filename), $output);
    }

    /**
     * Get file extension
     */
    public static function getExtension(string $file): string
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Get file dirrectory path
     */
    public static function getDirname(string $file): string
    {
        return pathinfo($file, PATHINFO_DIRNAME);
    }

    /**
     * Get filename only
     */
    public static function getName(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }

    /**
     * Get filename and extension
     */
    public static function getBasename(string $file): string
    {
        return pathinfo($file, PATHINFO_BASENAME);
    }
}
