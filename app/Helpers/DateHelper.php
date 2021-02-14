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
     * get date in custom format
     *
     * @param  string $f date format
     * @return string
     */
    public function date(string $f = 'Y-m-d'): string
    {
        return self::$date->format($f);
    }
    
    /**
     * get time
     *
     * @param  string $f time format
     * @return string
     */
    public function time(string $f = 'h:i:s a'): string
    {
        return self::$date->format($f);
    }
    
    /**
     * get time elapsed
     *
     * @param  int $level
     * @return string
     */
    public function time_elapsed(int $level = 1): string
    {
        return time_elapsed($this->timestamp(), $level);
    }
}
