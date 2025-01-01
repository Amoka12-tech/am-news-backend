FROM php:8.1-fpm
WORKDIR /var/www
RUN docker-php-ext-install pdo pdo_mysql
COPY . /var/www
