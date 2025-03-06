<?php

declare(strict_types=1);

namespace Core\Enums;

enum UploadFileError: string
{
    public const UPLOAD_ERR_INI_SIZE = 'Uploaded file exceeds the upload_max_filesize directive in php.ini';

    public const UPLOAD_ERR_FORM_SIZE = 'Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';

    public const UPLOAD_ERR_PARTIAL = 'Uploaded file was only partially uploaded.';

    public const UPLOAD_ERR_NO_FILE = 'No file was uploaded';

    public const UPLOAD_ERR_NO_TMP_DIR = 'Missing a temporary folder';

    public const UPLOAD_ERR_CANT_WRITE = 'Failed to write file to disk';

    public const UPLOAD_ERR_STOPPED_EXT = 'File upload stopped by extension';

    public const UPLOAD_ERR_UNKNOWN = 'Unknown error';

    public const UPLOAD_NO_ERR = 'No error';
}
