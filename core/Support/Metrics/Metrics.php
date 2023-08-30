<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support\Metrics;

use Closure;
use Core\Database\QueryBuilder;
use Core\Exceptions\InvalidAggregateException;
use Core\Exceptions\InvalidDateFormatException;
use Core\Exceptions\InvalidPeriodException;
use Core\Support\Metrics\Enums\Aggregate;
use Core\Support\Metrics\Enums\Period;
use DateTime;

/**
 * Metrics and trends generator
 */
class Metrics
{
    protected string $column = 'id';
    protected string|array $period;
    protected string $aggregate;
    protected string $dateColumn;
    protected ?string $labelColumn = null;
    protected string $driver;
    protected QueryBuilder $qb;
    protected int $interval = 0;
    protected ?Closure $subQuery = null;
    protected int $year;
    protected int $month;
    protected int $day;
    protected int $week;
    
    public function __construct(protected string $table)
    {
        $this->driver = config('app.env') === 'test'
            ? config('tests.database.driver')
            : config('database.driver');

        $this->qb = QueryBuilder::table($this->table);
        $this->dateColumn = $this->table . '.created_at';
        $this->period = Period::MONTH->value;
        $this->aggregate = Aggregate::COUNT->value;
        $this->year = carbon()->year;
        $this->month = carbon()->month;
        $this->week = carbon()->week;
        $this->day = carbon()->day;
    }

    public static function table(string $table): self
    {
        return new self($table);
    }

    public function subQuery(Closure $subQuery): self
    {
        $this->subQuery = $subQuery;
        return $this;
    }

    protected function by(string $period, int $interval = 0): self
    {
        $period = strtolower($period);

        if (!in_array($period, Period::values())) {
            throw new InvalidPeriodException();
        }

        $this->period = $period;
        $this->interval = $interval;
        return $this;
    }

    public function byDay(int $interval = 0): self
    {
        return $this->by(Period::DAY->value, $interval);
    }

    public function byWeek(int $interval = 0): self
    {
        return $this->by(Period::WEEK->value, $interval);
    }

    public function byMonth(int $interval = 0): self
    {
        return $this->by(Period::MONTH->value, $interval);
    }

    public function byYear(int $interval = 0): self
    {
        return $this->by(Period::YEAR->value, $interval);
    }

    public function between(string $start, string $end): self
    {
        $this->checkDateFormat([$start, $end]);
        $this->period = [$start, $end];
        return $this;
    }

    public function forDay(int $day): self
    {
        $this->day = $day;
        return $this;
    }

    public function forWeek(int $week): self
    {
        $this->week = $week;
        return $this;
    }

    public function forMonth(int $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function forYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    protected function aggregate(string $aggregate, string $column): self
    {
        $aggregate = strtolower($aggregate);

        if (!in_array($aggregate, Aggregate::values())) {
            throw new InvalidAggregateException();
        }

        $this->aggregate = $aggregate;
        $this->column = $this->table . '.' . $column;
        return $this;
    }

    public function count(string $column = 'id'): self
    {
        return $this->aggregate(Aggregate::COUNT->value, $column);
    }

    public function average(string $column): self
    {
        return $this->aggregate(Aggregate::AVERAGE->value, $column);
    }

    public function sum(string $column): self
    {
        return $this->aggregate(Aggregate::SUM->value, $column);
    }

    public function max(string $column): self
    {
        return $this->aggregate(Aggregate::MAX->value, $column);
    }

    public function min(string $column): self
    {
        return $this->aggregate(Aggregate::MIN->value, $column);
    }

    public function dateColumn(string $column): self
    {
        $this->dateColumn = $this->table . '.' . $column;
        return $this;
    }

    public function labelColumn(string $column): self
    {
        $this->labelColumn = $this->table . '.' . $column;
        return $this;
    }

    /**
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    protected function trendsData(): array
    {
        return match ($this->period) {
            Period::DAY->value => $this->qb
                ->select([$this->asData(), $this->asLabel(Period::DAY->value)])
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), $this->day);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), '>=', carbon()->subDays($this->interval)->day);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::WEEK->value => $this->qb
                ->select([$this->asData(), $this->asLabel(Period::WEEK->value)])
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), $this->week);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), '>=', carbon()->subWeeks($this->interval)->week);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::MONTH->value => $this->qb
                ->select([$this->asData(), $this->asLabel(Period::MONTH->value)])
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::MONTH->value), $this->month);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::MONTH->value), '>=', carbon()->subMonths($this->interval)->month);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::YEAR->value => $this->qb
                ->select([$this->asData(), $this->asLabel(Period::YEAR->value)])
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), $this->month);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), '>=', carbon()->subYears($this->interval)->year);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            default => [],
        };
    }

    protected function metricsData(): mixed
    {
        return match ($this->period) {
            Period::DAY->value => $this->qb
                ->select($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), $this->day);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), '>=', carbon()->subDays($this->interval)->day);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::WEEK->value => $this->qb
                ->select($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), $this->week);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), '>=', carbon()->subWeeks($this->interval)->week);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::MONTH->value => $this->qb
                ->select($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::MONTH->value), $this->month);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::MONTH->value), '>=', carbon()->subMonths($this->interval)->month);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::YEAR->value => $this->qb
                ->select($this->asData())
                ->subQueryWhen($this->interval === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), $this->month);
                })
                ->subQueryWhen($this->interval > 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), '>=', carbon()->subYears($this->interval)->year);
                })
                ->subQueryWhen(!is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            default => [],
        };
    }

    protected function asData(): string
    {
        return "$this->aggregate($this->column) as data";
    }

    protected function asLabel(string $label): string
    {
        return $this->formatPeriod($label) . " as label";
    }

    protected function formatPeriod(string $period): string
    {
        return match ($period) {
            Period::DAY->value => $this->driver === 'mysql' ? "weekday($this->dateColumn)" : "strftime($this->dateColumn)",
            Period::WEEK->value => $this->driver === 'mysql' ? "week($this->dateColumn)" : "strftime($this->dateColumn)",
            Period::MONTH->value => $this->driver === 'mysql' ? "month($this->dateColumn)" : "strftime($this->dateColumn)",
            Period::YEAR->value => $this->driver === 'mysql' ? "year($this->dateColumn)" : "strftime($this->dateColumn)",
            default => $this->driver === 'mysql' ? "day($this->dateColumn)" : "strftime($this->dateColumn)",
        };
    }

    protected function formatDate(array $data): array
    {
        return array_map(function ($data)  {
            if ($this->period === Period::MONTH->value) {
                $data->label = carbon($this->year . '-' . $data->label)->locale(config('app.lang'))->monthName;
            } elseif ($this->period === Period::DAY->value) {
                $data->label = carbon($this->year . '-' . $this->month . '-' . $data->label)->locale(config('app.lang'))->dayName;
            } elseif ($this->period === Period::WEEK->value) {
                $data->label = __('week') . ' ' . $data->label;
            } elseif ($this->period === Period::YEAR->value) {
                $data->label = intval($data->label);
            } else {
                $data->label = carbon($data->label)->locale(config('app.lang'))->toFormattedDateString();
            }

            return $data;
        }, $data);
    }

    /**
     * Generate metrics data
     */
    public function metrics(): mixed
    {
        $metricsData = $this->metricsData();
        return is_null($metricsData) ? 0 : $metricsData->data;
    }

    /**
     * Generate trends data for charts
     */
    public function trends(): array
    {
        $trendsData = is_null($this->labelColumn)
            ? $this->formatDate($this->trendsData())
            : $this->trendsData();

        $result = [];

        foreach ($trendsData as $data) {
            $result['labels'][] = $data->label;
            $result['data'][] = $data->data;
        }

        return $result;
    }

    protected function checkDateFormat(array $dates): void
    {
        foreach ($dates as $date) {
            $d = DateTime::createFromFormat('Y-m-d', $date);

            if (!$d || $d->format('Y-m-d') !== $date) {
                throw new InvalidDateFormatException();
            }
        }
    }
}
