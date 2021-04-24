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
        self::$date = Carbon::parse($date, Auth::get('timezone'))->locale(Auth::get('lang'));
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
     * get date and time in timestamp format
     *
     * @return string
     */
    public function timestamp(): string
    {
        return self::$date->toDateTimeString();
    }
    
    /**
     * get datetime in custom format
     *
     * @param  string $format
     * @return string
     */
    public function datetime(string $format): string
    {
        return self::$date->format($format);
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
