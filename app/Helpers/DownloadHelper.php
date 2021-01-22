<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Framework\Http\Response;

class DownloadHelper
{   
    /**
     * @var array $mimes_types
     */
    protected static array $mimes_types = [
        'htm' => 'text/html',
        'html' => 'text/html',
        'csv' => 'text/csv',
        'exe' => 'application/octet-stream',
        'zip' => 'application/zip',
        'doc' => 'application/msword',
        'xls' => 'application/vnd.ms-excel',
        'ppt' => 'application/vnd.ms-powerpoint',
        'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'pdf' => 'application/pdf',
        'jpg' => 'image/jpg',
        'jpeg' => 'image/jpg',
        'gif' => 'image/gif',
        'png' => 'image/png',
    ];

    /**
     * @var $filename
     */
    protected static $filename;

    /**
     * force browser to download file
     *
     * @param  string $filename
     * @return \App\Helpers\DownloadHelper
     */
    public static function init(string $filename, bool $real_file = false)
    {
        static::$filename = $filename;

        $filename = $real_file ? basename(static::$filename) : static::$filename;
        $file_extension = get_file_extension($filename);
        
        if (array_key_exists($file_extension, self::$mimes_types)) {
            $content_type = self::$mimes_types[$file_extension];
        } else {
            $content_type = 'text/plain';
        }

		Response::headers([
			'Content-Description' => 'File Transfer',
            'Content-Type' => $content_type,
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
        
        return new self();
    }
    
    /**
     * send file content
     *
     * @return void
     */
    public function send(): void
    {
        ob_clean();
        flush();
        readfile(static::$filename);

        exit();
    }
    
    /**
     * send CSV file
     *
     * @param  array $data
     * @param  array|null $headers
     * @return void
     */
    public function sendCSV(array $data, ?array $headers = null): void
    {
        $handle = fopen('php://output', 'w');

		//insert headers
		if (!is_null($headers)) {
            fputcsv($handle, $headers);
        }

		//insert rows
		foreach ($data as $row) {
			fputcsv($handle, $row);
		}

		fclose($handle);

		exit();
    }
    
    /**
     * send PDF file
     *
     * @param  string $html
     * @param  array|null $headers
     * @return void
     */
    public function sendPDF(string $html): void
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4');
        $dompdf->render();

        $handle = fopen('php://output', 'w');
        fwrite($handle, $dompdf->output());
		fclose($handle);

		exit();
    }
}
