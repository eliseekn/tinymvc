# TinyMVC

[![Latest Version on Packagist](https://img.shields.io/packagist/v/eliseekn/tinymvc.svg?style=flat-square)](https://packagist.org/packages/eliseekn/tinymvc)
[![Total Downloads](https://img.shields.io/packagist/dt/eliseekn/tinymvc.svg?style=flat-square)](https://packagist.org/packages/eliseekn/tinymvc)

TinyMVC is a PHP framework based on MVC architecture that helps you build easily and quickly powerful web applications and RESTful API.

## Requirements

```
PHP ^8.4
Node ^20.20
```

## Installation

1. Create new composer project

In your terminal :

```
composer create-project eliseekn/tinymvc project-name
```

1. Install packages dependencies

In your terminal :

```
cd ./project-name
yarn && yarn dev
```

## Your first application

1. Setup application

In your terminal :

```
cp .env.example .env
php console app:setup
```

1. Setup database

In your terminal :

```
php console migrations:run --seed
```

1. Start a local server development

In your terminal :

```
php console serve
```

For more console commands :

```
php console list
```

## Database layer

TinyMVC mixes the Laravel and Symfony approaches : the model is the query gateway, the entity is the typed result.

### Models (query gateway)

A model is a table-oriented query gateway built on the query builder :

```
php console make:model post
```

### Entities (typed results)

An entity is a typed class mapped to a table row. Generate one with :

```
php console make:entity post
```

The command prompts you for fields interactively, or you can pass them directly :

```
php console make:entity post --fields="title:string, views:int, published:bool, published_at:datetime" -m
```

Options : `--fields` to define typed properties (`string`, `text`, `int`, `float`, `bool`, `datetime`, ...) and `-m` to create the migration.

### Querying and persisting

Query through the model, get entities back using `toEntity()`, and persist through the entity itself :

```php
use App\Database\Entities\Post as PostEntity;
use App\Database\Models\Post;

class Post extends Model
{
    public static function findByTitle(string $title): ?PostEntity
    {
        return self::query()->findBy('title', $title)?->toEntity(PostEntity::class);
    }
}

// query using the model, get a typed entity as result
$post = Post::findByTitle('Hello TinyMVC');
$post->getTitle();
$post->getPublishedAt(); // Carbon instance

// persist using the entity
$post->setTitle('Updated title')->save();
$post->delete();

// create using the entity factory
$post = PostEntity::factory()->create(['title' => 'Hello TinyMVC']);
```

Entity properties are automatically hydrated from database columns (`published_at` becomes `publishedAt`) and cast to their declared types, including `Carbon` dates and backed enums. Columns without a matching property (from a join for example) stay available through `$entity->get('column')`. You can bridge both worlds at any time with `$model->toEntity(Post::class)`, `Entity::fromModel($model)` and `$entity->toModel()`.

## License

[MIT](https://opensource.org/licenses/MIT)

## Copyright

2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
