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
        self::$date = Carbon::parse($date, user_session()->timezone)->locale(user_session()->lang);
        return new self();
    }
    
    /**
     * get formatted date
     *
     * @return mixed
     */
    public function get()
    {
        return self::$date;
    }
    
    /**
     * get date in human readable format
     *
     * @return string
     */
    public function human(): string
    {
        return self::$date->isoFormat('MMM Do, YYYY');
    }
    
    /**
     * get date in 'Y-m-d' format
     *
     * @return string
     */
    public function dateOnly(): string
    {
        return self::$date->toDateTimeString();
    }
}
