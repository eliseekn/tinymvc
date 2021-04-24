<?php

namespace App\Helpers;

use Framework\Routing\View;

class Report
{
    /**
     * export data to file
     *
     * @param  string $filename
     * @param  array $data data to export
	 * @param  array $vars headers and colunms names
     * @return void
     */
    public static function generate(string $filename, array $data, array $vars): void
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
