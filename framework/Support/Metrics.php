<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Support;

use Carbon\Carbon;
use Framework\Database\Builder;

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
     * @return array
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    public function getTrends(string $type, string $column, string $trends, int $interval = 0): array
    {
        switch ($trends) {
            case 'days':
                return $interval > 0 ?
                    Builder::select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->from($this->table)
                        ->where('DATE(created_at)', '>=', Carbon::now()->subDays($interval)->toDateString())
                        ->groupBy('DAYNAME(created_at)')
                        ->execute()
                        ->fetchAll() :
                    Builder::select($type . '(' . $column . ') AS value')
                        ->from($this->table)
                        ->where('DATE(created_at)', '=', Carbon::now()->toDateString())
                        ->execute()
                        ->fetchAll();

            case 'weeks':
                return $interval > 0 ?
                    Builder::select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->from($this->table)
                        ->where('DATE(created_at)', '>', Carbon::now()->subWeeks($interval)->toDateString())
                        ->and('MONTH(created_at)', '=', Carbon::now()->month)
                        ->and('YEAR(created_at)', '=', Carbon::now()->year)
                        ->groupBy('DAYNAME(created_at)')
                        ->execute()
                        ->fetchAll() :
                    Builder::select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                        ->from($this->table)
                        ->where('DATE(created_at)', '>', Carbon::now()->subWeeks()->toDateString())
                        ->and('MONTH(created_at)', '=', Carbon::now()->month)
                        ->and('YEAR(created_at)', '=', Carbon::now()->year)
                        ->groupBy('DAYNAME(created_at)')
                        ->execute()
                        ->fetchAll();

            case 'months':
                return $interval > 0 ?
                    Builder::select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month')
                        ->from($this->table)
                        ->where('MONTH(created_at)', '>', Carbon::now()->subMonths($interval)->month)
                        ->and('YEAR(created_at)', '=', Carbon::now()->year)
                        ->groupBy('MONTHNAME(created_at)')
                        ->execute()
                        ->fetchAll() :
                    Builder::select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month')
                        ->from($this->table)
                        ->where('YEAR(created_at)', '=', Carbon::now()->year)
                        ->groupBy('MONTHNAME(created_at)')
                        ->execute()
                        ->fetchAll();
            
            case 'years':
                return $interval > 0 ? 
                    Builder::select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year')
                        ->from($this->table)
                        ->where('YEAR(created_at)', '>', Carbon::now()->subYears($interval)->year)
                        ->groupBy('YEAR(created_at)')
                        ->execute()
                        ->fetchAll() :
                    Builder::select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year')
                        ->from($this->table)
                        ->groupBy('YEAR(created_at)')
                        ->execute()
                        ->fetchAll();
        }
    }
}
