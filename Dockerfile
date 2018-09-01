FROM php:7.2.9-fpm

COPY composer.lock composer.json /var/www/

COPY database /var/www/database

WORKDIR /var/www
RUN apt-get update
RUN apt-get install git unzip -y

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts \
    && rm composer.phar

COPY . /var/www

RUN chown -R www-data:www-data \
        /var/www/backend/storage \
        /var/www/backend/bootstrap/cache

RUN php artisan optimize
