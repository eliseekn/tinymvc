<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Support;

use Framework\ORM\Builder;

/**
 * Metrics generator
 */
class Metrics
{
    /**
     * @var string
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
     * @return array
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     */
    public function getTrends(string $type, string $column, string $trends): array
    {
        switch ($trends) {
            case 'days':
                return Builder::select($type . '(' . $column . ') AS value')
                    ->from($this->table)
                    ->where('DATE(created_at)', '=', date('Y-m-d'))
                    ->execute()
                    ->fetchAll();

            case 'weeks':
                return Builder::select($type . '(' . $column . ') AS value', 'DAYNAME(created_at) AS day')
                    ->from($this->table)
                    ->where('DATE(created_at)', '>', date('Y-m-d', strtotime('-7 days')))
                    ->andWhere('MONTH(created_at)', '=', date('m'))
                    ->andWhere('YEAR(created_at)', '=', date('Y'))
                    ->groupBy('DAYNAME(created_at)')
                    ->orderBy('DAYNAME(created_at)', 'ASC')
                    ->execute()
                    ->fetchAll();

            case 'months':
                return Builder::select($type . '(' . $column . ') AS value', 'MONTHNAME(created_at) AS month')
                    ->from($this->table)
                    ->where('YEAR(created_at)', '=', date('Y'))
                    ->groupBy('MONTHNAME(created_at)')
                    ->orderBy('MONTHNAME(created_at)', 'ASC')
                    ->execute()
                    ->fetchAll();
            
            case 'years':
                return Builder::select($type . '(' . $column . ') AS value', 'YEAR(created_at) AS year')
                    ->from($this->table)
                    ->groupBy('YEAR(created_at)')
                    ->orderBy('YEAR(created_at)', 'ASC')
                    ->execute()
                    ->fetchAll();
        }
    }
}
