# TinyMVC

[![Latest Version on Packagist](https://img.shields.io/packagist/v/eliseekn/tinymvc.svg?style=flat-square)](https://packagist.org/packages/eliseekn/tinymvc)
[![Total Downloads](https://img.shields.io/packagist/dt/eliseekn/tinymvc.svg?style=flat-square)](https://packagist.org/packages/eliseekn/tinymvc)

TinyMVC is a PHP framework based on MVC architecture that helps you build easily and quickly powerful web applications and REST API.

## Requirements
```
PHP 8.1.x
MySQL or SQLite
Comoser
Yarn or NPM
```

## Installation

1\. Create new composer project

On your console :
```
composer create-project eliseekn/tinymvc project-name
```

2\. Install packages dependencies

On your console :
```
cd ./project-name
yarn && yarn build
```

## Your first application

1\. Setup application

On your console :
```
cp .env.example .env
php console app:setup
```
2\. Setup database

On your console :
```
php console db:create
php console migrations:run --seed
```
3\. Start a local server development

On your console :
```
php console serve
```
For more console commands :
```
php console list
```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2019 - 2023 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)
