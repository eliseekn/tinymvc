# TinyMVC

TinyMVC is a PHP framework based on MVC architecture that helps you build easly and quickly powerful web applications and RESTful API.

## Requirements
```
PHP >= 7.2
MySQL
composer
yarn
```

## Installation

1\. Download your TinyMVC framework copy [here](https://github.com/eliseekn/tinymvc/archive/master.zip)

2\. Setup your web server configuration

For ***Apache*** server, edit your ```.htaccess``` with the following lines: 

```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php
```

For ***Nginx*** server, add the following to your server declaration

```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```
3\. Install PHP and Javascript packages dependencies

On your terminal run:
```
cd ./tinymvc
composer install && yarn
```
4\. Setup your application

On your terminal run:
```
php console app:setup
```
Then start a local server development by running this command:
```
php console server:start
```
For more console commands run:
```
php console list
```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2021 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)
