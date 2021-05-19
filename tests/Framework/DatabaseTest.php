<?php
declare(strict_types = 1);

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

    public function testQueryBuilderSelect()
    {
        $this->assertEquals('SELECT * FROM users', $this->qb->select('*')->toSQL()[0]);
        $this->resetQuery();
    }

    public function testQueryBuilderWhere()
    {
        $data = $this->qb->select('*')->where('id', 1)->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('*')->where('id', 1)->fetchAll();
        $this->assertEquals(1, count($data));
        $this->resetQuery();

        $data = $this->qb->select('*')->whereRaw('id = 1')->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('*')->whereRaw('id = ?', [1])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->qb->select('*')->whereRaw('id = :id', ['id' => 1])->fetch();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

    }

    public function testRepositorySelect()
    {
        $this->assertEquals('SELECT * FROM users', $this->repository->select('*')->toSQL()[0]);
        $this->resetQuery();
    }

    public function testRepositoryFind()
    {
        $data = $this->repository->find(1)->one();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findOne(1);
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->find(1)->all();
        $this->assertEquals(1, count($data));
        $this->resetQuery();

        $data = $this->repository->findAll(1);
        $this->assertEquals(1, count($data));
        $this->resetQuery();

        $data = $this->repository->findRaw('id = 1')->one();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findRaw('id = ?', [1])->one();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();

        $data = $this->repository->findRaw('id = :id', ['id' => 1])->one();
        $this->assertEquals('admin', $data->name);
        $this->resetQuery();
    }
}
