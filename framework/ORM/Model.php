<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\ORM;

use Framework\HTTP\Request;
use Framework\Support\Pager;

/**
 * Model
 * 
 * Simplify use of query builder
 */
class Model
{
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = '';

    /**
     * find row by column id
     *
     * @param  int $id
     * @return mixed
     */
    public static function find(int $id)
    {
        return self::findWhere('id', $id);
    }

    /**
     * check if row exists
     *
     * @param  string $column
     * @param  string $value
     * @return bool
     */
    public static function exists(string $column, string $value): bool
    {
        return isset(self::findWhere($column, $value)->$column);
    }
    
    /**
     * fetch single row
     *
     * @param  string $column
     * @param  string $value
     * @return mixed
     */
    public static function findWhere(string $column, string $value)
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals($column, $value)
            ->fetchSingle();
    }

    /**
     * fetch all rows
     *
     * @param  array $order direction order ASC or DESC
     * @return mixed
     */
    public static function findAll(array $order = ['id', 'DESC'])
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->orderBy($order[0], $order[1])
            ->fetchAll();
    }

    /**
     * find rows with where clause
     *
     * @param  string $column
     * @param  string $value
     * @param  array $order_by direction order ASC or DESC
     * @return mixed
     */
    public static function findAllWhere(string $column, string $value, array $order_by = ['id', 'DESC'])
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals($column, $value)
            ->orderBy($order_by[0], $order_by[1])
            ->fetchAll();
    }

    /**
     * find rows with limit clause
     *
     * @param  int $limit
     * @param  int $offset
     * @param  array $order_by direction order ASC or DESC
     * @return mixed
     */
    public static function findRange(int $limit, int $offset, array $order_by = ['id', 'DESC'])
    {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->orderBy($order_by[0], $order_by[1])
            ->limit($limit, $offset)
            ->fetchAll();
    }

    /**
     * find range rows
     *
     * @param  int $limit
     * @param  array $order_by direction order ASC or DESC
     * @return mixed
     */
    public static function findFirstOf(int $limit, array $order_by = ['id', 'DESC'])
    {
        return self::findRange(0, $limit, $order_by);
    }

    /**
     * find rows with limit and where clauses
     *
     * @param  int $limit
     * @param  int $offset
     * @param  string $column
     * @param  string $value
     * @param  array $order_by direction order ASC or DESC
     * @return mixed
     */
    public static function findRangeWhere(
        int $limit,
        int $offset,
        string $column,
        string $value,
        array $order_by = ['id', 'DESC']
    ) {
        return Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals($column, $value)
            ->orderBy($order_by[0], $order_by[1])
            ->limit($limit, $offset)
            ->fetchAll();
    }

    /**
     * find range rows with where clause
     *
     * @param  int $limit
     * @param  string $column
     * @param  string $value
     * @param  array $order_by direction order ASC or DESC
     * @return mixed
     */
    public static function findFirstOfWhere(
        int $limit,
        string $column,
        string $value,
        array $order_by = ['id', 'DESC']
    ) {
        return self::findRangeWhere(0, $limit, $column, $value, $order_by);
    }
    
    /**
     * delete row with WHERE clause
     *
     * @param  string $column
     * @param  string $value
     * @return void
     */
    public static function deleteWhere(string $column, string $value): void
    {
        Query::DB()
            ->deleteFrom(static::$table)
            ->whereEquals($column, $value)
            ->executeQuery();
    }
    
    /**
     * delete row
     *
     * @param  int $id row id
     * @return void
     */
    public static function delete(int $id): void
    {
        self::deleteWhere('id', $id);
    }
    
    /**
     * update data with where clause
     *
     * @param  string $column
     * @param  string $value
     * @param  array $data
     * @return void
     */
    public static function updateWhere(string $column, string $value, array $data): void
    {
        Query::DB()
            ->update(static::$table)
            ->set($data)
            ->whereEquals($column, $value)
            ->executeQuery();
    }
    
    /**
     * update data
     *
     * @param  int $id
     * @param  array $data
     * @return void
     */
    public static function update(int $id, array $data): void
    {
        self::updateWhere('id', $id, $data);
    }
    
    /**
     * insert data
     *
     * @param  array $data data to insert
     * @return void
     */
    public static function insert(array $data): void
    {
        Query::DB()
            ->insert(static::$table, $data)
            ->executeQuery();
    }
    
    /**
     * generate pagination
     *
     * @param  mixed $items_per_pages
     * @param  array $order_by direction order ASC or DESC
     * @return mixed returns new pager class instance
     */
    public static function paginate(int $items_per_pages, array $order_by = ['id', 'DESC']): Pager
    {
        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');

        $total_items = Query::DB()
            ->select('*')
            ->from(static::$table)
            ->count();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            self::findRange($pagination['first_item'], $items_per_pages, $order_by) :
            self::findAll($order_by);

        return new Pager($items, $pagination);
    }

    /**
     * generate pagination with where clause
     *
     * @param  int $items_per_pages
     * @param  string $column
     * @param  string $value
     * @param  array $order_by direction order ASC or DESC
     * @return mixed returns new pager class instance
     */
    public static function paginateWhere(
        int $items_per_pages,
        string $column,
        string $value,
        array $order_by = ['id', 'DESC']
    ): Pager {
        $page = empty(Request::getQuery('page')) ? 1 : Request::getQuery('page');

        $total_items = Query::DB()
            ->select('*')
            ->from(static::$table)
            ->whereEquals($column, $value)
            ->count();

        $pagination = generate_pagination($page, $total_items, $items_per_pages);

        $items = $items_per_pages > 0 ? 
            self::findRangeWhere(
                $pagination['first_item'],
                $items_per_pages,
                $column,
                $value,
                $order_by
            ) : 
            self::findAllWhere($column, $value, $order_by);

        return new Pager($items, $pagination);
    }
}
