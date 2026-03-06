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
    public static function generateImage(string $filepath, int $width, int $height): bool
    {
        ImageGenerator::create(Canvas::create($width, $height), $filepath);

        return storage(File::getDirname($filepath))->isFile(DIRECTORY_SEPARATOR.File::getBasename($filepath));
    }

    public static function generatePDF(string $filepath, string $html): bool
    {
        $dompdf = new Dompdf;
        $dompdf->loadHtml($html);
        $dompdf->render();
        $output = $dompdf->output();

        return storage(File::getDirname($filepath))->writeFile(DIRECTORY_SEPARATOR.File::getBasename($filepath), $output);
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
