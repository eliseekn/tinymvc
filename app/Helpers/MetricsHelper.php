<?php

namespace App\Helpers;

use Framework\Support\Metrics;

class MetricsHelper
{    
    /**
     * get items count
     *
     * @param  string $table
     * @param  string $column
     * @param  string $trends days, weeks, months or years
     * @return mixed
     */
    public static function getCount(string $table, string $column, string $trends)
    {
        return (new Metrics($table))->getCount($column, $trends);
    }
    
    /**
     * get items sum value
     *
     * @param  string $table
     * @param  string $column
     * @param  string $trends days, weeks, months or years
     * @return mixed
     */
    public static function getSum(string $table, string $column, string $trends)
    {
        return (new Metrics($table))->getSum($column, $trends);
    }
    
    /**
     * get items average value
     *
     * @param  string $table
     * @param  string $column
     * @param  string $trends days, weeks, months or years
     * @return mixed
     */
    public static function getAverage(string $table, string $column, string $trends)
    {
        return (new Metrics($table))->getAverage($column, $trends);
    }
    
    /**
     * get items min value
     *
     * @param  string $table
     * @param  string $column
     * @param  string $trends days, weeks, months or years
     * @return mixed
     */
    public static function getMin(string $table, string $column, string $trends)
    {
        return (new Metrics($table))->getMin($column, $trends);
    }
    
    /**
     * get items max value
     *
     * @param  string $table
     * @param  string $column
     * @param  string $trends days, weeks, months or years
     * @return mixed
     */
    public static function getMax(string $table, string $column, string $trends)
    {
        return (new Metrics($table))->getMax($column, $trends);
    }
}
