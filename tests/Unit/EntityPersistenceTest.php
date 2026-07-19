<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit;

use Core\Database\Entity;
use Core\Database\QueryBuilder;
use Core\Database\Repository;
use PHPUnit\Framework\TestCase;

class EntityPersistenceTest extends TestCase
{
    protected function setUp(): void
    {
        QueryBuilder::table(FakeArticle::table());
        QueryBuilder::dropTable(FakeArticle::table())->execute();

        QueryBuilder::createTable(FakeArticle::table())
            ->column('id', 'INTEGER')->primaryKey()->autoIncrement()
            ->column('title', 'VARCHAR(255)')->notNull()
            ->column('views', 'INTEGER')->default(0)
            ->timestamps()
            ->migrate();
    }

    protected function tearDown(): void
    {
        QueryBuilder::table(FakeArticle::table());
        QueryBuilder::dropTable(FakeArticle::table())->execute();
    }

    public function test_can_save_and_convert_model_to_entity(): void
    {
        $article = (new FakeArticle)->setTitle('Hello TinyMVC')->setViews(10)->save();

        $this->assertInstanceOf(FakeArticle::class, $article);
        $this->assertNotNull($article->getId());
        $this->assertNotNull($article->getCreatedAt());

        $found = new Repository(FakeArticle::table())
            ->find($article->getId())
            ?->toEntity(FakeArticle::class);

        $this->assertInstanceOf(FakeArticle::class, $found);
        $this->assertSame('Hello TinyMVC', $found->getTitle());
        $this->assertSame(10, $found->getViews());
    }

    public function test_can_update_entity(): void
    {
        $article = (new FakeArticle)->setTitle('Hello TinyMVC')->save();
        assert($article instanceof FakeArticle);

        $this->assertTrue($article->wasChanged('title') === false);

        $article->setTitle('Updated title')->save();

        $found = new Repository(FakeArticle::table())
            ->find($article->getId())
            ?->toEntity(FakeArticle::class);

        $this->assertSame('Updated title', $found?->getTitle());
        $this->assertCount(1, new Repository(FakeArticle::table())->findAll('>', 0));
    }

    public function test_can_delete_entity(): void
    {
        $article = (new FakeArticle)->setTitle('Hello TinyMVC')->save();
        assert($article instanceof FakeArticle);

        $this->assertTrue($article->delete());
        $this->assertEmpty(new Repository(FakeArticle::table())->findAll('>', 0));
        $this->assertFalse((new FakeArticle)->delete());
    }
}

class FakeArticle extends Entity
{
    protected ?string $title = null;

    protected ?int $views = null;

    public static function table(): string
    {
        return 'fake_articles';
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }
}
