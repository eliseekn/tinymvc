<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Support;

use Carbon\Carbon;
use Framework\Database\QueryBuilder;

/**
 * Metrics generator
 */
class Metrics
{
    /**
     * @var string $table
     */
    private $table;

    /**
     * metrics constants
     */
    public const COUNT = 'COUNT';
    public const AVERAGE = 'AVG';
    public const SUM = 'SUM';
    public const MAX = 'MAX';
    public const MIN = 'MIN';
    public const DAYS = 'days';
    public const WEEKS = 'weeks';
    public const MONTHS = 'months';
    public const YEARS = 'years';
    
    /**
     * __construct
     *
     * @param  string $table
     * @return void
     */
    public function __construct(string $table)
    {
        $this->table = $table;
    }
    
    /**
     * get items value according to trends
     *
     * @param  string $type
     * @param  string $column
     * @param  string $trends
     * @param  int $interval
     * @param  array|null $query
     * @return array
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    public function getTrends(string $type, string $column, string $trends, int $interval = 0, array $query = null): array
    {
        switch ($trends) {
            case 'days':
                return $interval > 0 ?
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->where('DATE(created_at)', '>=', Carbon::now()->subDays($interval)->toDateString())
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('DAYNAME(created_at)')
                        ->fetchAll() :
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value')
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->where('DATE(created_at)', Carbon::now()->toDateString())
                        ->fetchAll();

            case 'weeks':
                return $interval > 0 ?
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->where('DATE(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                        ->and('MONTH(created_at)', Carbon::now()->month)
                        ->and('YEAR(created_at)', Carbon::now()->year)
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('DAYNAME(created_at)')
                        ->fetchAll() :
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->where('DATE(created_at)', '>', Carbon::now()->subWeek()->toDateString())
                        ->and('MONTH(created_at)', Carbon::now()->month)
                        ->and('YEAR(created_at)', Carbon::now()->year)
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('DAYNAME(created_at)')
                        ->fetchAll();

            case 'months':
                return $interval > 0 ?
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month')
                        ->where('MONTH(created_at)', '>', Carbon::now()->subMonths($interval)->month)
                        ->and('YEAR(created_at)', Carbon::now()->year)
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('MONTHNAME(created_at)')
                        ->fetchAll() :
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month')
                        ->where('YEAR(created_at)', Carbon::now()->year)
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('MONTHNAME(created_at)')
                        ->fetchAll();
            
            default:
                return $interval > 0 ? 
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year')
                        ->where('YEAR(created_at)', '>', Carbon::now()->subYears($interval)->year)
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('YEAR(created_at)')
                        ->fetchAll() :
                    QueryBuilder::table($this->table)
                        ->select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year')
                        ->subQuery(function ($q) use ($query) {
                            if (!is_null($query) && !empty($query)) {
                                $q->rawQuery($query[0], $query[1]);
                            }
                        })
                        ->groupBy('YEAR(created_at)')
                        ->fetchAll();
        }
    }
}
