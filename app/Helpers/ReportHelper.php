<?php

namespace App\Helpers;

use Framework\Routing\View;

class ReportHelper
{    
    /**
     * import data from csv
     *
     * @param  string $filename
	 * @param  string $model
	 * @param  array $vars headers and colunms names
     * @return void
	 * @link   https://www.php.net/manual/en/function.str-getcsv.php
     */
    public static function import(string $filename, string $model, array $vars): void
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

            $function = ['App\Database\Models\\' . $model, 'insert'];
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

		if (get_file_extension($filename) === 'csv') {
			DownloadHelper::init($filename)->sendCSV($to_export, array_values($vars));
		} else {
			DownloadHelper::init($filename)->sendPDF(View::getContent('pdf/report', [
				'data' => $to_export,
				'headers' => array_values($vars)
			]));
		}
    }
}
