<?php

use Core\Database\QueryBuilder;
use Core\Database\Repository;
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{    
    /**
     * @var \Core\Database\QueryBuilder
     */
    private $qb;

    /**
     * @var \Core\Database\Repository
     */
    private $repository;

    function setUp(): void
    {
        $this->qb = QueryBuilder::table('users');
        $this->repository = new Repository('users');
    }

    private function resetQuery()
    {
        $this->qb->setQuery('');
        $this->repository->setQuery('');
    }

    /**
     * Tests on query builder
     */
    public function testQueryBuilderQuery()
    {
        list($query, $args) = $this->qb->select('*')->toSQL();

        $this->assertEquals('SELECT * FROM users', $query);
        $this->resetQuery();

        list($query, $args) = $this->qb->selectWhere('id', 1)->toSQL();

        $this->assertEquals('SELECT * FROM users WHERE id = ?', $query);
        $this->assertEquals(1, $args[0]);
        $this->resetQuery();

        list($query, $args) = $this->qb->select('name')->whereColumn('id')->isNull()->toSQL();

        $this->assertEquals('SELECT name FROM users WHERE id IS NULL', $query);
        $this->resetQuery();

        list($query, $args) = $this->qb->select('name')->whereColumn('id')->in([1, 2, 3])->toSQL();

        $this->assertEquals('SELECT name FROM users WHERE id IN (?, ?, ?)', $query);
        $this->assertEquals(1, $args[0]);
        $this->assertEquals(2, $args[1]);
        $this->assertEquals(3, $args[2]);
        $this->resetQuery();

        //add more tests cases
    }

    public function testQueryBuilderWhere()
    {
        $data = $this->qb->select('name')->where('id', 1)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->where('id', 1)->fetchAll();
        $this->assertEquals(1, count($data));
        $this->resetQuery();

        $data = $this->qb->select('name')->whereRaw('id = 1')->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereRaw('id = ?', [1])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereRaw('id = :id', ['id' => 1])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereNot('id', 2)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->selectWhere('id', 1)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->isNull()->fetch();
        $this->assertFalse($data);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->notNull()->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->in([1, 2, 3])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->notIn([2, 3])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->between(1, 3)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('id')->notBetween(2, 3)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('name')->like('admin')->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->whereColumn('name')->notLike('admin')->fetchAll();
        $this->assertEmpty($data);
        $this->resetQuery();

        $data = $this->qb->select('name')->where('id', 1)->and('name', 'admin')->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->where('id', 2)->or('name', 'test')->fetch();
        $this->assertFalse($data);
        $this->resetQuery();

        //add more tests cases
    }

    public function testQueryBuilderHaving()
    {
        $data = $this->qb->select('name')->having('name', 'admin')->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->havingRaw('name = ?', ['admin'])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('name')->havingRaw('name = :name', ['name' => 'admin'])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();
    }

    public function testQueryBuilderExists()
    {
        $data = $this->qb->selectWhere('id', 1)->exists();
        $this->assertTrue($data);
        $this->resetQuery();
    }

    /**
     * Tests on repository
     */
    public function testRepositoryQuery()
    {
        list($query, $args) = $this->repository->select('*')->toSQL();

        $this->assertEquals('SELECT * FROM users', $query);
        $this->resetQuery();

        list($query, $args) = $this->repository->select('*')->where('id', 1)->toSQL();

        $this->assertEquals('SELECT * FROM users WHERE id = ?', $query);
        $this->assertEquals(1, $args[0]);
        $this->resetQuery();

        list($query, $args) = $this->repository->select('name')->where('id', 'null')->toSQL();

        $this->assertEquals('SELECT name FROM users WHERE id IS NULL', $query);
        $this->resetQuery();

        list($query, $args) = $this->repository->select('name')->whereIn('id', [1, 2, 3])->toSQL();

        $this->assertEquals('SELECT name FROM users WHERE id IN (?, ?, ?)', $query);
        $this->assertEquals(1, $args[0]);
        $this->assertEquals(2, $args[1]);
        $this->assertEquals(3, $args[2]);
        $this->resetQuery();

        list($query, $args) = $this->repository->select('name')->where('id', 'in', [1, 2, 3])->toSQL();

        $this->assertEquals('SELECT name FROM users WHERE id IN (?, ?, ?)', $query);
        $this->assertEquals(1, $args[0]);
        $this->assertEquals(2, $args[1]);
        $this->assertEquals(3, $args[2]);
        $this->resetQuery();

        //add more tests cases
    }

    public function testRepositoryFind()
    {
        $data = $this->repository->find(1);
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findAll(1);
        $this->assertEquals(1, count($data));
        $this->resetQuery();

        $data = $this->repository->findRaw('id = 1')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findRaw('id = ?', [1])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findRaw('id = :id', ['id' => 1])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', 'null')->get();
        $this->assertFalse($data);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereNull('id')->get();
        $this->assertFalse($data);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', '!null')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereNotNull('id')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', 'in', [1, 2, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereIn('id', [1, 2, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', '!in', [2, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereNotIn('id', [2, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', 'between', [1, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereBetween('id', 1, 3)->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', '!between', [2, 3])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereNotBetween('id', 2, 3)->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('name', 'like', 'admin')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereLike('name', 'admin')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('name', '!like', 'admin')->getAll();
        $this->assertEmpty($data);
        $this->resetQuery();

        $data = $this->repository->select('name')->whereNotLike('name', 'admin')->getAll();
        $this->assertEmpty($data);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', 1)->and('name', 'admin')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->where('id', 2)->or('name', 'test')->get();
        $this->assertFalse($data);
        $this->resetQuery();

        //add more tests cases
    }

    public function testRepositoryHaving()
    {
        $data = $this->repository->select('name')->having('name', 'admin')->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->havingRaw('name = ?', ['admin'])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->select('name')->havingRaw('name = :name', ['name' => 'admin'])->get();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();
    }

    public function testRepositoryExists()
    {
        $data = $this->repository->select('*')->where('id', 1)->exists();
        $this->assertTrue($data);
        $this->resetQuery();
    }

    //add more tests case
}
