<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Carbon\Carbon;
use Core\Database\QueryBuilder;

/**
 * Metrics generator
 */
class Metrics
{
    private $table;

    public const COUNT = 'COUNT';
    public const AVERAGE = 'AVG';
    public const SUM = 'SUM';
    public const MAX = 'MAX';
    public const MIN = 'MIN';
    
    public const TODAY = 'today';
    public const DAY = 'day';
    public const WEEKDAY = 'weekday';
    public const WEEK = 'week';
    public const MONTH = 'month';
    public const YEAR = 'year';
    
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    
    /**
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    public function trends(string $column, string $type, string $period, int $interval = 0, array $query = null)
    {
        $qb = QueryBuilder::table($this->table);
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $week = Carbon::now()->weekOfYear;

        switch ($period) {
            case self::TODAY:
                $qb->select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day');

                return $qb->where('DATE(created_at)', Carbon::now()->toDateString())
                    ->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy('DAYNAME(created_at)')
                    ->fetchAll();

            case self::DAY:
                $qb->select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day');

                $interval > 0
                    ? $qb->where('DATE(created_at)', '>=', Carbon::now()->subDays($interval)->toDateString())
                    : $qb->where('YEAR(created_at)', $year)->and('MONTH(created_at)', $month)->and('WEEK(created_at)', $week);

                return $qb->subQuery(function ($q) use ($query) {
                    if (!is_null($query) && !empty($query)) {
                        $q->rawQuery($query[0], $query[1]);
                    }
                })
                    ->groupBy('DAYNAME(created_at)')
                    ->fetchAll();

            case self::WEEKDAY:
                $qb->select($type . '(' . $column . ') AS value', 'WEEKDAY(created_at) AS weekday');

                $interval > 0
                    ? $qb->where('DATE(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                    : $qb->where('MONTH(created_at)', $month)->and('YEAR(created_at)', $year);

                return $qb->subQuery(function ($q) use ($query) {
                    if (!is_null($query) && !empty($query)) {
                        $q->rawQuery($query[0], $query[1]);
                    }
                })
                    ->groupBy('WEEKDAY(created_at)')
                    ->fetchAll();

            case self::WEEK:
                $qb->select($type . '(' . $column . ') AS value', 'WEEK(created_at) AS week');

                $interval > 0
                    ? $qb->where('DATE(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                    : $qb->where('MONTH(created_at)', $month)->and('YEAR(created_at)', $year);

                return $qb->subQuery(function ($q) use ($query) {
                    if (!is_null($query) && !empty($query)) {
                        $q->rawQuery($query[0], $query[1]);
                    }
                })
                    ->groupBy('WEEK(created_at)')
                    ->fetchAll();
    
            case self::MONTH:
                $qb->select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month');

                $interval > 0
                    ? $qb->where('MONTH(created_at)', '>', Carbon::now()->subMonths($interval)->month)
                    : $qb->where('YEAR(created_at)', $year);
            
                return $qb->subQuery(function ($q) use ($query) {
                    if (!is_null($query) && !empty($query)) {
                        $q->rawQuery($query[0], $query[1]);
                    }
                })
                    ->groupBy('MONTHNAME(created_at)')
                    ->fetchAll();
    
            case self::YEAR:
                $qb->select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year');

                if ($interval > 0) {
                    $qb->where('YEAR(created_at)', '>', Carbon::now()->subYears($interval)->year);
                }
                    
                return $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy('YEAR(created_at)')
                    ->fetchAll();
                    
            default:
                return [];
        }
    }
}
