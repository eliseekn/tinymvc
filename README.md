# TinyMVC

TinyMVC is a PHP framework based on MVC architecture that helps you build easly and quickly powerful web applications and RESTful API.

## Requirements
```PHP v7.2 or greater```

## License
[MIT](https://opensource.org/licenses/MIT)

## Copyright
2019-2020 © N'Guessan Kouadio Elisée (eliseekn@gmail.com)

# Installation

1\. Download your TinyMVC framework copy

[Download](https://github.com/eliseekn/TinyMVC/archive/v2.zip)

2\. Setup web server

For ***Apache*** server, edit your ```.htaccess``` with the following lines: 

```
RewriteEngine on
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-l
RewriteRule ^(.*)$ index.php [QSA]
```

For ***Nginx*** server, add the following to your server declaration

```
server {
    location / {
        try_files $uri $uri/ /index.php;
    }
}
```

# Configuration

Main configuration file is located in ```config/app.php```.

## Routes
