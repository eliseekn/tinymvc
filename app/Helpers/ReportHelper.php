<?php

namespace App\Helpers;

use Framework\HTTP\Response;
use Framework\Support\Storage;

class ReportHelper
{    
    /**
     * import data from csv
     *
     * @param  string $filename
	 * @param  array $function method to execute
	 * @param  array $vars headers and colunms names
     * @return void
	 * @link   https://www.php.net/manual/en/function.str-getcsv.php
     */
    public static function import(string $filename, array $function, array $vars): void
    {
		$csv = array_map('str_getcsv', file($filename));

		array_walk($csv, function(&$a) use ($csv) {
			$a = array_combine($csv[0], $a);
		});

		//remove header
		array_shift($csv);

		foreach ($csv as $row) {
			$to_import = [];

			foreach ($vars as $key => $value) {
				$to_import[$key] = $row[$value];
			}

			call_user_func_array($function, [$to_import]);
		}
    }
    
    /**
     * export data to file
     *
     * @param  string $filename
     * @param  array $data data to export
	 * @param  array $vars headers and colunms names
     * @return void
	 * @link   https://www.php.net/manual/en/function.fputcsv.php
     */
    public static function export(string $filename, array $data, array $vars): void
    {
		$to_export = [];

		foreach ($data as $d) {
			$tmp_data = [];

			foreach (array_keys($vars) as $column) {
				$tmp_data[] = $d->$column;
			}

			$to_export[] = $tmp_data;
		}

		if (end(explode('.', $$filename)) === 'csv') {
			DownloadHelper::init($filename)->sendCSV($to_export, array_values($vars));
		} else {
			DownloadHelper::init($filename)->sendPDF(Storage::path('views')->readFile('pdf/report.php'));
		}

		/* Response::sendHeaders([
			'Content-Description' => 'File Transfer',
			'Content-Type' => 'text/csv',
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
			'Expires' => '0'
		]);

		$handle = fopen('php://output', 'w');

		//insert headers
		fputcsv($handle, array_values($vars));

		//insert rows
		foreach ($to_export as $row) {
			fputcsv($handle, $row);
		}

		fclose($handle);

		exit(); */

		//generate_csv($filename, $to_export, array_values($vars));
    }
}
