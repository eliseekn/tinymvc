<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Dompdf\Options;
use Framework\HTTP\Response;

class DownloadHelper
{   
    /**
     * @var $mimes_types known mimes types
     * @link https://stackoverflow.com/a/32885706
     */
    protected static $mimes_types = [
        "htm" => "text/html",
        "html" => "text/html",
        "exe" => "application/octet-stream",
        "zip" => "application/zip",
        "doc" => "application/msword",
        "jpg" => "image/jpg",
        "php" => "text/plain",
        "xls" => "application/vnd.ms-excel",
        "ppt" => "application/vnd.ms-powerpoint",
        "gif" => "image/gif",
        "pdf" => "application/pdf",
        "txt" => "text/plain",
        "png" => "image/png",
        "jpeg"=> "image/jpg"
    ];

    /**
     * @var $filename
     */
    protected static $filename;

    /**
     * force browser to download file
     *
     * @param  string $filename
     * @return mixed
     */
    public static function init(string $filename, bool $real_file = false)
    {
        static::$filename = $filename;

		Response::sendHeaders([
			'Content-Description' => 'File Transfer',
            'Content-Type' => end(explode('.', static::$filename)),
			'Content-Disposition' => 'attachment; filename="' . $real_file ? print(basename(static::$filename)) : print(static::$filename) . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);
        
        return new self();
    }
    
    /**
     * send file content
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
     * @param  array $headers
     * @return void
     */
    public function sendCSV(array $data, array $headers): void
    {
        $handle = fopen('php://output', 'w');

		//insert headers
		fputcsv($handle, $headers);

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
     * @return void
     */
    public function sendPDF(string $html): void
    {
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $handle = fopen('php://output', 'w');
        fwrite($handle, $dompdf->output());
		fclose($handle);

		exit();
    }
}
