<?php

namespace App\Helpers;

use Framework\Console\Maker;
use Framework\Console\Database;

class ConsoleHelper
{    
    /**
     * execute cli arguments from browser
     *
     * @param  array $options
     * @return mixed
     */
    public static function execute(array $options)
    {
        if (
            array_key_exists('database', $options) &&
            !array_key_exists('make', $options)
        ) {
            unset($options['database']);
            Database::handle($options);
        }
        
        else if (
            array_key_exists('make', $options) &&
            !array_key_exists('database', $options)
        ) {
            unset($options['make']);
            Maker::handle($options);
        }
    }
}