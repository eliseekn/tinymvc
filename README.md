# TinyMVC

TinyMVC is a PHP framework based on MVC architecture that helps you build easly and quickly powerful web applications and RESTful API.

## Requirements
```
PHP >= 7.2
MySQL server
Node
Composer
Yarn
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

## Documentation
After installation open your browser and go to ```http://localhost/tinymvc/docs```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2021 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)
