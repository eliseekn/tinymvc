# TinyMVC

TinyMVC is a PHP framework based on MVC architecture that helps you build easly and quickly powerful web applications and RESTful API.

## Requirements
```
PHP >= 7.2
MySQL or SQLite
composer
yarn
```

## Installation

1\. Download a TinyMVC framework copy [here](https://github.com/eliseekn/tinymvc/archive/master.zip)

2\. Setup your web server configuration

For ***Apache*** server, edit your ```.htaccess``` with the following lines: 

```
<IfModule mod_rewrite.c>
    RewriteEngine on

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect request to main controller
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-l
    RewriteRule ^(.*)$ index.php
</IfModule>
```

For ***Nginx*** server, add the following to your server declaration

```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```
3\. Install packages dependencies

On your terminal:
```
cd ./tinymvc
composer install && yarn
```

## Your first application

1\. Setup application

On your terminal:
```
php console app:setup
```
2\. Setup database

On your terminal:
```
php console migrations:run --seed
```
3\. Start a local server development

On your terminal:
```
php console server:start
```
For more console commands:
```
php console list
```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2021 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)
