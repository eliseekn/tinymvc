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
    public const WEEK = 'week';
    public const MONTH = 'month';
    public const YEAR = 'year';

    private const DAYS = [
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'saturday'
    ];

    private const MONTHS = [
        'december',
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
    ];
    
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    
    /**
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    public function getTrends(string $column, string $type, string $period, int $interval = 0, array $query = null)
    {
        $qb = QueryBuilder::table($this->table);
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $week = Carbon::now()->weekOfYear;

        switch ($period) {
            case self::TODAY:
                $data = $qb->select($type . '(' . $column . ') AS data', $this->getPeriod(self::DAY))
                    ->where('date(created_at)', Carbon::now()->toDateString())
                    ->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy(self::DAY)
                    ->orderBy(self::DAY, 'asc')
                    ->fetchAll();

                return $this->formatDate($data, self::DAY);

            case self::DAY:
                $qb->select($type . '(' . $column . ') AS data', $this->getPeriod(self::DAY));

                $interval > 0
                    ? $qb->where('date(created_at)', '>=', Carbon::now()->subDays($interval)->toDateString())
                    : $qb->whereColumn($this->formatPeriod(self::YEAR))->like($year)
                        ->andColumn($this->formatPeriod(self::MONTH))->like($month)
                        ->andColumn($this->formatPeriod(self::WEEK))->like($week);

                $data = $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy(self::DAY)
                    ->orderBy(self::DAY, 'asc')
                    ->fetchAll();

                return $this->formatDate($data, self::DAY);
                    
            case self::WEEK:
                $qb->select($type . '(' . $column . ') AS data', $this->getPeriod(self::WEEK));

                $interval > 0
                    ? $qb->where('date(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                    : $qb->whereColumn($this->formatPeriod(self::YEAR))->like($year)
                        ->andColumn($this->formatPeriod(self::MONTH))->like($month);

                $data = $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy(self::WEEK)
                    ->orderBy(self::WEEK, 'asc')
                    ->fetchAll();

                return $this->formatDate($data, self::WEEK);
    
            case self::MONTH:
                $qb->select($type . '(' . $column . ') AS data', $this->getPeriod(self::MONTH));

                $interval > 0
                    ? $qb->where($this->formatPeriod(self::MONTH), '>', Carbon::now()->subMonths($interval)->month)
                    : $qb->where($this->formatPeriod(self::YEAR), $year);
            
                $data = $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy(self::MONTH)
                    ->orderBy(self::MONTH, 'asc')
                    ->fetchAll();

                return $this->formatDate($data, self::MONTH);
    
            case self::YEAR:
                $qb->select($type . '(' . $column . ') AS data', $this->getPeriod(self::YEAR));

                if ($interval > 0) {
                    $qb->where($this->formatPeriod(self::YEAR), '>', Carbon::now()->subYears($interval)->year);
                }
                    
                $data = $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->groupBy(self::YEAR)
                    ->orderBy(self::YEAR, 'asc')
                    ->fetchAll();

                return $this->formatDate($data, self::YEAR);
                    
            default: return [];
        }
    }

    public function get(string $column, string $type, string $period, int $interval = 0, array $query = null)
    {
        $qb = QueryBuilder::table($this->table);
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $week = Carbon::now()->weekOfYear;

        switch ($period) {
            case self::TODAY:
                return $qb->select($type . '(' . $column . ') AS data')
                    ->where('date(created_at)', Carbon::now()->toDateString())
                    ->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->fetch();

            case self::DAY:
                $qb->select($type . '(' . $column . ') AS data');

                $interval > 0
                    ? $qb->where('date(created_at)', '>=', Carbon::now()->subDays($interval)->toDateString())
                    : $qb->whereColumn($this->formatPeriod(self::YEAR))->like($year)
                        ->andColumn($this->formatPeriod(self::MONTH))->like($month)
                        ->andColumn($this->formatPeriod(self::WEEK))->like($week);

                return $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->fetch();

            case self::WEEK:
                $qb->select($type . '(' . $column . ') AS data');

                $interval > 0
                    ? $qb->where('date(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                    : $qb->whereColumn($this->formatPeriod(self::YEAR))->like($year)
                        ->andColumn($this->formatPeriod(self::MONTH))->like($month);

                return $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->fetch();
    
            case self::MONTH:
                $qb->select($type . '(' . $column . ') AS data');

                $interval > 0
                    ? $qb->where($this->formatPeriod(self::MONTH), '>', Carbon::now()->subMonths($interval)->month)
                    : $qb->where($this->formatPeriod(self::YEAR), $year);
            
                return $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->fetch();
    
            case self::YEAR:
                $qb->select($type . '(' . $column . ') AS data');

                if ($interval > 0) {
                    $qb->where($this->formatPeriod(self::YEAR), '>', Carbon::now()->subYears($interval)->year);
                }
                    
                return $qb->subQuery(function ($q) use ($query) {
                        if (!is_null($query) && !empty($query)) {
                            $q->rawQuery($query[0], $query[1]);
                        }
                    })
                    ->fetch();
                    
            default: return [];
        }
    }

    private function getPeriod(string $period)
    {
        return $this->formatPeriod($period) . ' AS ' . $period;
    }

    private function formatPeriod(string $period)
    {
        switch ($period) {
            case self::DAY:
                return config('database.driver') === 'mysql'
                    ? 'weekday(created_at)'
                    : "strftime('%w', created_at)";

            case self::WEEK:
                return config('database.driver') === 'mysql'
                    ? 'week(created_at)'
                    : "strftime('%W', created_at)";

            case self::MONTH:
                return config('database.driver') === 'mysql'
                    ? 'month(created_at)'
                    : "strftime('%m', created_at)";

            case self::YEAR:
                return config('database.driver') === 'mysql'
                    ? 'year(created_at)'
                    : "strftime('%Y', created_at)";

            default: return '';
        } 
    }

    private function formatDate(array $data, string $period)
    {
        $data = array_map(function ($data) use ($period) {
            $data->data = intval($data->data);

            if ($period === self::MONTH) {
                $data->$period = self::MONTHS[intval($data->$period)];
            } else if ($period === self::DAY || $period === self::TODAY) {
                $data->$period = config('database.driver') === 'mysql' 
                    ? self::DAYS[intval($data->$period) + 1]
                    : self::DAYS[intval($data->$period)];
            } else if ($period === self::WEEK) {
                $data->$period = __('week') . ' ' . $data->$period;
            } else {
                $data->$period = intval($data->$period);
            }

            return $data;
        }, $data);

        return $data;
    }
}
