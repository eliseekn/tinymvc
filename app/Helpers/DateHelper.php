<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{   
    /**
     * @var mixed $date
     */
    protected static $date;

    /**
     * format date using locale and timezone
     *
     * @param  mixed $date
     * @return \App\Helpers\DateHelper
     */
    public static function format($date = null): self
    {
        self::$date = Carbon::parse($date, auth()->timezone)->locale(auth()->lang);
        return new self();
    }
    
    /**
     * get date in human readable format
     *
     * @return string
     */
    public function human(): string
    {
        return ucfirst(self::$date->isoFormat('MMM Do, YYYY'));
    }
    
    /**
     * get date and time in SQL like format
     *
     * @return string
     */
    public function timestamp(): string
    {
        return self::$date->toDateTimeString();
    }
    
    /**
     * get date in 'Y-m-d' format
     *
     * @return string
     */
    public function date(): string
    {
        return self::$date->toDateString();
    }
    
    /**
     * get time format
     *
     * @return string
     */
    public function time(): string
    {
        return self::$date->toTimeString();
    }
}
