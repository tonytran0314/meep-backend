FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl pdo pdo_mysql

EXPOSE 8080

CMD [ "php", "artisan", "reverb:start", "--host=0.0.0.0", "--debug" ]