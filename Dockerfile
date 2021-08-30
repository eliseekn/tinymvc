FROM php:7.4-fpm

RUN apt update -y && apt upgrade -y
RUN apt install -y git curl libpng-dev libonig-dev libxml2-dev zip unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo_mysql mbstring

WORKDIR /app
COPY composer.json .

RUN composer install --no-scripts
COPY . ./

EXPOSE 8080

CMD php console server:start --host=0.0.0.0 --port=8080