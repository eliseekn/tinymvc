<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Tests\Unit;

use App\Enums\UserRole;
use Carbon\Carbon;
use Core\Database\Entity;
use Core\Database\Model;
use PHPUnit\Framework\TestCase;

class EntityTest extends TestCase
{
    public function test_can_hydrate_from_row_with_types_casting(): void
    {
        $entity = FakePost::fromRow([
            'id' => '1',
            'title' => 'Hello TinyMVC',
            'views' => '10',
            'published' => '1',
            'role' => 'admin',
            'published_at' => '2026-07-05 10:00:00',
            'created_at' => '2026-07-05 09:00:00',
        ]);

        $this->assertSame(1, $entity->getId());
        $this->assertSame('Hello TinyMVC', $entity->getTitle());
        $this->assertSame(10, $entity->getViews());
        $this->assertTrue($entity->getPublished());
        $this->assertSame(UserRole::ADMIN, $entity->getRole());
        $this->assertInstanceOf(Carbon::class, $entity->getPublishedAt());
        $this->assertSame('2026-07-05 10:00:00', $entity->getPublishedAt()->toDateTimeString());
        $this->assertInstanceOf(Carbon::class, $entity->getCreatedAt());
    }

    public function test_hydrate_ignores_unknown_columns(): void
    {
        $entity = FakePost::fromRow(['id' => 1, 'unknown_column' => 'value']);

        $this->assertSame(1, $entity->getId());
    }

    public function test_can_convert_to_array_of_columns(): void
    {
        $entity = (new FakePost)
            ->setTitle('Hello TinyMVC')
            ->setViews(10)
            ->setPublished(true)
            ->setRole(UserRole::ADMIN)
            ->setPublishedAt(Carbon::parse('2026-07-05 10:00:00'));

        $attributes = $entity->toArray();

        $this->assertSame('Hello TinyMVC', $attributes['title']);
        $this->assertSame(10, $attributes['views']);
        $this->assertSame(1, $attributes['published']);
        $this->assertSame('admin', $attributes['role']);
        $this->assertSame('2026-07-05 10:00:00', $attributes['published_at']);
        $this->assertArrayHasKey('id', $attributes);
        $this->assertNull($attributes['id']);
    }

    public function test_can_get_attributes_by_column_name(): void
    {
        $entity = FakePost::fromRow([
            'id' => 1,
            'title' => 'Hello TinyMVC',
            'published_at' => '2026-07-05 10:00:00',
            'author' => 'eliseekn',
        ]);

        $this->assertSame(1, $entity->get('id'));
        $this->assertSame('Hello TinyMVC', $entity->get('title'));
        $this->assertInstanceOf(Carbon::class, $entity->get('published_at'));
        $this->assertSame('eliseekn', $entity->get('author'));
        $this->assertNull($entity->get('unknown'));
    }

    public function test_can_get_only_a_subset_of_attributes(): void
    {
        $entity = FakePost::fromRow(['id' => 1, 'title' => 'Hello TinyMVC', 'views' => 10]);

        $this->assertEquals(['id' => 1, 'views' => 10], $entity->only(['id', 'views']));
    }

    public function test_can_track_changed_attributes(): void
    {
        $entity = FakePost::fromRow(['id' => 1, 'title' => 'Hello TinyMVC', 'views' => 10]);

        $this->assertFalse($entity->wasChanged('title'));

        $entity->setTitle('Updated title');

        $this->assertTrue($entity->wasChanged('title'));
        $this->assertTrue($entity->wasChanged(['title', 'views']));
        $this->assertFalse($entity->wasChanged('views'));
        $this->assertSame('Hello TinyMVC', $entity->getOriginal('title'));

        $entity->syncOriginal();

        $this->assertFalse($entity->wasChanged('title'));
    }

    public function test_can_convert_to_and_from_model(): void
    {
        $entity = FakePost::fromRow(['id' => 1, 'title' => 'Hello TinyMVC']);
        $model = $entity->toModel();

        $this->assertInstanceOf(Model::class, $model);
        $this->assertSame(FakePost::table(), $model->table);
        $this->assertSame(1, $model->getId());
        $this->assertSame('Hello TinyMVC', $model->getAttributes('title'));

        $fromModel = FakePost::fromModel($model);

        $this->assertSame(1, $fromModel->getId());
        $this->assertSame('Hello TinyMVC', $fromModel->getTitle());
    }
}

class FakePost extends Entity
{
    protected ?string $title = null;

    protected ?int $views = null;

    protected ?bool $published = null;

    protected ?UserRole $role = null;

    protected ?Carbon $publishedAt = null;

    public static function table(): string
    {
        return 'posts';
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

    public function getPublished(): ?bool
    {
        return $this->published;
    }

    public function setPublished(?bool $published): self
    {
        $this->published = $published;

        return $this;
    }

    public function getRole(): ?UserRole
    {
        return $this->role;
    }

    public function setRole(?UserRole $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function getPublishedAt(): ?Carbon
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?Carbon $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }
}
