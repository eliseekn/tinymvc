<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Database\Metrics;

use Closure;
use Core\Database\Connection\Connection;
use Core\Database\Metrics\Enums\Aggregate;
use Core\Database\Metrics\Enums\Period;
use Core\Database\QueryBuilder;
use Core\Exceptions\InvalidAggregateException;
use Core\Exceptions\InvalidDateFormatException;
use Core\Exceptions\InvalidPeriodException;
use Core\Exceptions\InvalidVariationsCountException;

/**
 * Metrics and trends generator.
 */
class Metrics
{
    use DatesFunctions;

    protected string $column = 'id';

    protected string|array|null $period;

    protected string $aggregate;

    protected string $dateColumn;

    protected ?string $labelColumn = null;

    protected int $count = 0;

    protected int $year;

    protected int $month;

    protected int $day;

    protected int $week;

    protected string $dateIsoFormat = 'YYYY-MM-DD';

    protected bool $fillMissingData = false;

    protected array $missingDataLabels = [];

    protected int $missingDataValue = 0;

    protected string $groupBy;

    protected string $groupedData = '';

    protected array $groupedDataLabels = [];

    protected QueryBuilder $qb;

    protected string $driver;

    private ?Closure $subQuery = null;

    public function __construct(protected string $table)
    {
        $this->driver = Connection::getInstance()->getDriver();
        $this->qb = QueryBuilder::table($this->table);
        $this->dateColumn = $this->table.'.created_at';
        $this->period = Period::MONTH->value;
        $this->aggregate = Aggregate::COUNT->value;
        $this->year = carbon()->year;
        $this->month = carbon()->month;
        $this->week = carbon()->week;
        $this->day = carbon()->day;
        $this->groupBy = Period::DAY->value;
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

    protected function by(string $period, int $count = 0): self
    {
        $period = strtolower($period);

        if (! in_array($period, Period::values())) {
            throw new InvalidPeriodException;
        }

        $this->period = $period;
        $this->count = $count;

        return $this;
    }

    /**
     * @throws InvalidPeriodException
     */
    public function byDay(int $count = 0): self
    {
        return $this->by(Period::DAY->value, $count);
    }

    /**
     * @throws InvalidPeriodException
     */
    public function byWeek(int $count = 0): self
    {
        return $this->by(Period::WEEK->value, $count);
    }

    /**
     * @throws InvalidPeriodException
     */
    public function byMonth(int $count = 0): self
    {
        return $this->by(Period::MONTH->value, $count);
    }

    /**
     * @throws InvalidPeriodException
     */
    public function byYear(int $count = 0): self
    {
        return $this->by(Period::YEAR->value, $count);
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function between(string $start, string $end, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        $this->checkDateFormat([$start, $end]);
        $this->period = [$start, $end];
        $this->dateIsoFormat = $dateIsoFormat;

        return $this;
    }

    /**
     * @throws InvalidDateFormatException
     */
    public function from(string $date, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this->between($date, carbon()->format('Y-m-d'), $dateIsoFormat);
    }

    protected function groupBy(string $period): self
    {
        $this->groupBy = $period;

        return $this;
    }

    public function groupByYear(): self
    {
        return $this->groupBy(Period::YEAR->value);
    }

    public function groupByMonth(): self
    {
        return $this->groupBy(Period::MONTH->value);
    }

    public function groupByWeek(): self
    {
        return $this->groupBy(Period::WEEK->value);
    }

    public function groupByDay(): self
    {
        return $this->groupBy(Period::DAY->value);
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

    /**
     * @throws InvalidAggregateException
     */
    protected function aggregate(string $aggregate, string $column): self
    {
        $aggregate = strtolower($aggregate);

        if (! in_array($aggregate, Aggregate::values())) {
            throw new InvalidAggregateException;
        }

        $this->aggregate = $aggregate;
        $this->column = $this->table.'.'.$column;

        return $this;
    }

    /**
     * @throws InvalidAggregateException
     */
    public function count(string $column = 'id'): self
    {
        return $this->aggregate(Aggregate::COUNT->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     */
    public function average(string $column): self
    {
        return $this->aggregate(Aggregate::AVERAGE->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     */
    public function sum(string $column): self
    {
        return $this->aggregate(Aggregate::SUM->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     */
    public function max(string $column): self
    {
        return $this->aggregate(Aggregate::MAX->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     */
    public function min(string $column): self
    {
        return $this->aggregate(Aggregate::MIN->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    protected function countBy(string $period, string $column = 'id', int $count = 0): self
    {
        return $this
            ->by($period, $count)
            ->aggregate(Aggregate::COUNT->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    protected function averageBy(string $period, string $column = 'id', int $count = 0): self
    {
        return $this
            ->by($period, $count)
            ->aggregate(Aggregate::AVERAGE->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    protected function sumBy(string $period, string $column = 'id', int $count = 0): self
    {
        return $this
            ->by($period, $count)
            ->aggregate(Aggregate::SUM->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    protected function maxBy(string $period, string $column = 'id', int $count = 0): self
    {
        return $this
            ->by($period, $count)
            ->aggregate(Aggregate::MAX->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    protected function minBy(string $period, string $column = 'id', int $count = 0): self
    {
        return $this
            ->by($period, $count)
            ->aggregate(Aggregate::MIN->value, $column);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function countByDay(string $column = 'id', int $count = 0): self
    {
        return $this->countBy(Period::DAY->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function countByWeek(string $column = 'id', int $count = 0): self
    {
        return $this->countBy(Period::WEEK->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function countByMonth(string $column = 'id', int $count = 0): self
    {
        return $this->countBy(Period::MONTH->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function countByYear(string $column = 'id', int $count = 0): self
    {
        return $this->countBy(Period::YEAR->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function sumByDay(string $column, int $count = 0): self
    {
        return $this->sumBy(Period::DAY->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function sumByWeek(string $column, int $count = 0): self
    {
        return $this->sumBy(Period::WEEK->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function sumByMonth(string $column, int $count = 0): self
    {
        return $this->sumBy(Period::MONTH->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function sumByYear(string $column, int $count = 0): self
    {
        return $this->sumBy(Period::YEAR->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function averageByDay(string $column, int $count = 0): self
    {
        return $this->averageBy(Period::DAY->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function averageByWeek(string $column, int $count = 0): self
    {
        return $this->averageBy(Period::WEEK->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function averageByMonth(string $column, int $count = 0): self
    {
        return $this->averageBy(Period::MONTH->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function averageByYear(string $column, int $count = 0): self
    {
        return $this->averageBy(Period::YEAR->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function maxByDay(string $column, int $count = 0): self
    {
        return $this->maxBy(Period::DAY->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function maxByWeek(string $column, int $count = 0): self
    {
        return $this->maxBy(Period::WEEK->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function maxByMonth(string $column, int $count = 0): self
    {
        return $this->maxBy(Period::MONTH->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function maxByYear(string $column, int $count = 0): self
    {
        return $this->maxBy(Period::YEAR->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function minByDay(string $column, int $count = 0): self
    {
        return $this->minBy(Period::DAY->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function minByWeek(string $column, int $count = 0): self
    {
        return $this->minBy(Period::WEEK->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function minByMonth(string $column, int $count = 0): self
    {
        return $this->minBy(Period::MONTH->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidPeriodException
     */
    public function minByYear(string $column, int $count = 0): self
    {
        return $this->minBy(Period::YEAR->value, $column, $count);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function countBetween(array $period, string $column = 'id', string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->count($column)
            ->between($period[0], $period[1], $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function sumBetween(array $period, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->sum($column)
            ->between($period[0], $period[1], $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function averageBetween(array $period, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->average($column)
            ->between($period[0], $period[1], $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function maxBetween(array $period, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->max($column)
            ->between($period[0], $period[1], $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function minBetween(array $period, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->min($column)
            ->between($period[0], $period[1], $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function countFrom(string $date, string $column = 'id', string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->count($column)
            ->from($date, $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function sumFrom(string $date, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->sum($column)
            ->from($date, $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function averageFrom(string $date, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->average($column)
            ->from($date, $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function maxFrom(string $date, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->max($column)
            ->from($date, $dateIsoFormat);
    }

    /**
     * @throws InvalidAggregateException
     * @throws InvalidDateFormatException
     */
    public function minFrom(string $date, string $column, string $dateIsoFormat = 'YYYY-MM-DD'): self
    {
        return $this
            ->min($column)
            ->from($date, $dateIsoFormat);
    }

    public function dateColumn(string $column): self
    {
        $this->dateColumn = $this->table.'.'.$column;

        return $this;
    }

    public function labelColumn(string $column): self
    {
        $this->labelColumn = $this->table.'.'.$column;

        return $this;
    }

    public function fillMissingData(int $missingDataValue = 0, array $missingDataLabels = []): self
    {
        $this->fillMissingData = true;
        $this->missingDataValue = $missingDataValue;
        $this->missingDataLabels = $missingDataLabels;

        return $this;
    }

    protected function metricsData(): mixed
    {
        if (is_array($this->period)) {
            return $this->qb
                ->selectRaw($this->asData())
                ->whereColumn($this->formatDateColumn())
                ->between($this->period[0], $this->period[1])
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->fetch();
        }

        return match ($this->period) {
            Period::DAY->value => $this->qb
                ->selectRaw($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), $this->day);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::TODAY->value))
                        ->between($this->getDayPeriod()[0], $this->getDayPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::WEEK->value => $this->qb
                ->selectRaw($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), $this->day);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::WEEK->value))
                        ->between($this->getWeekPeriod()[0], $this->getWeekPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::MONTH->value => $this->qb
                ->selectRaw($this->asData())
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::MONTH->value), $this->day);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::MONTH->value))
                        ->between($this->getMonthPeriod()[0], $this->getMonthPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            Period::YEAR->value => $this->qb
                ->select($this->asData())
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), $this->year);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->whereColumn($this->formatPeriod(Period::YEAR->value))
                        ->between(carbon()->subYears($this->count)->year, $this->year);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->fetch(),

            default => $this->qb
                ->select($this->asData())
                ->fetch(),
        };
    }

    /**
     * @link   https://www.tutsmake.com/mysql-get-data-of-current-date-week-month-year/
     *         https://www.tutsmake.com/query-for-get-data-of-last-day-week-month-year-mysql/
     */
    protected function trendsData(): array
    {
        if (is_array($this->period)) {
            return $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel($this->formatDateColumn()).$this->groupedData)
                ->whereColumn($this->formatDateColumn())
                ->between($this->period[0], $this->period[1])
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll();
        }

        return match ($this->period) {
            Period::DAY->value => $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel(Period::DAY->value).$this->groupedData)
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::TODAY->value), $this->day);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::TODAY->value))
                        ->between($this->getDayPeriod()[0], $this->getDayPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::WEEK->value => $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel(Period::WEEK->value).$this->groupedData)
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->and($this->formatPeriod(Period::MONTH->value), $this->month)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::WEEK->value), $this->week);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::WEEK->value))
                        ->between($this->getWeekPeriod()[0], $this->getWeekPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::MONTH->value => $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel(Period::MONTH->value).$this->groupedData)
                ->where($this->formatPeriod(Period::YEAR->value), $this->year)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->and($this->formatPeriod(Period::MONTH->value), $this->month);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->andColumn($this->formatPeriod(Period::MONTH->value))
                        ->between($this->getMonthPeriod()[0], $this->getMonthPeriod()[1]);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            Period::YEAR->value => $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel(Period::YEAR->value).$this->groupedData)
                ->subQueryWhen($this->count === 1, function (QueryBuilder $qb) {
                    $qb->where($this->formatPeriod(Period::YEAR->value), $this->year);
                })
                ->subQueryWhen($this->count > 1, function (QueryBuilder $qb) {
                    $qb->whereColumn($this->formatPeriod(Period::YEAR->value))
                        ->between(carbon()->subYears($this->count)->year, $this->year);
                })
                ->subQueryWhen(! is_null($this->subQuery), $this->subQuery)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),

            default => $this->qb
                ->selectRaw($this->asData().', '.$this->asLabel().$this->groupedData)
                ->groupBy('label')
                ->orderBy('label', 'asc')
                ->fetchAll(),
        };
    }

    public function groupData(array $dataLabels, string $aggregate): self
    {
        $this->groupedDataLabels = $dataLabels;
        $result = [];

        foreach ($dataLabels as $key => $value) {
            $result[] = $aggregate.'('.$this->column.' = "'.$value.'")'." as data$key";
        }

        $this->groupedData = ', '.implode(', ', $result);

        return $this;
    }

    protected function asData(string $name = 'data'): string
    {
        return "$this->aggregate($this->column) as $name";
    }

    protected function asLabel(?string $label = null, bool $format = true): string
    {
        if (is_null($this->labelColumn)) {
            $label = ! $format ? $label : $this->formatPeriod($label);
        } else {
            $label = $this->labelColumn;
        }

        return $label.' as label';
    }

    protected function populateMissingDataForPeriod(array $data, bool $inPercent = false, string $dataLabel = 'data'): array
    {
        $dates = $this->getCustomPeriod();
        $data = collect($data);
        $result = [];

        foreach ($dates as $date) {
            $dataForDate = $data->where('label', $date)->first();

            if ($dataForDate) {
                $result[] = [
                    'label' => $dataForDate['label'],
                    'data' => (int) $dataForDate[$dataLabel],
                ];
            } else {
                $result[] = [
                    'label' => $date,
                    'data' => $this->missingDataValue,
                ];
            }
        }

        $result = $this->formatDate($result);

        return $this->formatTrends($result, $inPercent);
    }

    protected function populateMissingData(array $labels, array $data): array
    {
        $result = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($labels as $label => $defaultValue) {
            $key = array_search($label, $data['labels']);
            $result['labels'][] = $label;

            if ($key !== false) {
                $result['data'][] = $data['data'][$key];
            } else {
                $result['data'][] = $defaultValue;
            }
        }

        return $result;
    }

    /**
     * Generate metrics data
     */
    public function metrics(): mixed
    {
        $metricsData = $this->metricsData();

        return is_null($metricsData) ? 0 : ($metricsData->data ?? 0);
    }

    /**
     * Generate metrics data with variations
     *
     * @throws InvalidVariationsCountException
     * @throws InvalidPeriodException|InvalidAggregateException
     */
    public function metricsWithVariations(int $previousCount, string $previousPeriod, bool $inPercent = false): array
    {
        if (! in_array($previousPeriod, Period::values())) {
            throw new InvalidPeriodException;
        }

        if ($previousCount <= 0) {
            throw new InvalidVariationsCountException;
        }

        $metrics = (new self($this->table))
            ->by($previousPeriod, $previousCount)
            ->aggregate($this->aggregate, str_replace($this->table.'.', '', $this->column));

        $variations = match ($previousPeriod) {
            Period::DAY->value => $metrics
                ->forDay(carbon()->subDays($previousCount)->day)
                ->metrics(),

            Period::WEEK->value => $metrics
                ->forWeek(carbon()->subWeeks($previousCount)->week)
                ->metrics(),

            Period::MONTH->value => $metrics
                ->forMonth(carbon()->subMonths($previousCount)->month)
                ->metrics(),

            default => $metrics
                ->forYear(carbon()->subYears($previousCount)->year)
                ->metrics(),
        };

        $result['count'] = $this->metrics();
        $result['variation'] = [];

        $value = $result['count'] - $variations;

        if ($inPercent && $variations > 0) {
            $value = (abs($value) / $variations) * 100 .'%';
        }

        if ($value > 0) {
            $result['variation'] = [
                'type' => 'increase',
                'value' => $value,
            ];
        } elseif ($value < 0) {
            $result['variation'] = [
                'type' => 'decrease',
                'value' => abs($value),
            ];
        }

        return $result;
    }

    protected function trendsWithMergedData(bool $inPercent = false): array
    {
        $result = [];

        $trendsData = $this->trendsData();

        $trendsData = array_map(fn ($datum) => (array) $datum, $trendsData);
        $data = [$this->getFormattedTrendsData($trendsData, $inPercent)];

        foreach ($this->groupedDataLabels as $key => $value) {
            $data[] = $this->getFormattedTrendsData($trendsData, $inPercent, "data$key");
        }

        foreach ($data as $key => $value) {
            $result['labels'] = $value['labels'];

            if ($key === 0) {
                $result['data']['total'] = $value['data'];
            } else {
                $result['data'][$this->groupedDataLabels[$key - 1]] = $value['data'];
            }
        }

        return $result;
    }

    protected function getFormattedTrendsData(array $trendsData, bool $inPercent = false, string $dataLabel = 'data'): array
    {
        if (! $this->fillMissingData) {
            $trendsData = $this->formatDate($trendsData);

            return $this->formatTrends($trendsData, $inPercent, $dataLabel);
        } else {
            if (! is_null($this->labelColumn)) {
                $trendsData = $this->formatTrends($trendsData, $inPercent, $dataLabel);

                return $this->populateMissingData($this->getLabelsData(), $trendsData);
            }

            if (is_array($this->period)) {
                return $this->populateMissingDataForPeriod($trendsData, $inPercent, $dataLabel);
            }

            if (is_string($this->period)) {
                $trendsData = $this->formatDate($trendsData);

                return $this->populateMissingData($this->getPeriod(), $this->formatTrends($trendsData, $inPercent, $dataLabel));
            }
        }

        return [
            'labels' => [],
            'data' => [],
        ];
    }

    /**
     * Generate trends data for charts
     */
    public function trends(bool $inPercent = false): array
    {
        $trendsData = $this->trendsData();

        $trendsData = array_map(fn ($datum) => (array) $datum, $trendsData);

        if (! empty($this->groupedDataLabels)) {
            return $this->trendsWithMergedData($inPercent);
        }

        return $this->getFormattedTrendsData($trendsData, $inPercent);
    }

    protected function formatTrends(array $data, bool $inPercent = false, string $dataLabel = 'data'): array
    {
        $total = 0;

        $result = [
            'labels' => [],
            'data' => [],
        ];

        foreach ($data as $datum) {
            $result['labels'][] = $datum['label'];
            $result['data'][] = (int) $datum[$dataLabel];
            $total += $datum[$dataLabel];
        }

        if (! $inPercent) {
            return $result;
        }

        $percentData = [];

        foreach ($result['data'] as $item) {
            $percentData[] = round(($item / $total) * 100, 2);
        }

        $result['data'] = $percentData;

        return $result;
    }
}
