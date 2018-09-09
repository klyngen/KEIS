FROM php:7.2.9-fpm

COPY composer.lock composer.json /var/www/

#COPY database /var/www/database

WORKDIR /var/www
RUN apt-get update
RUN apt-get install git unzip -y

# LETS GET SOME NEEDED PHP PACKAGES 
RUN docker-php-ext-install pdo pdo_mysql

COPY . /var/www/

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php \
    && php composer-setup.php \
    && php -r "unlink('composer-setup.php');" \
    && php composer.phar install --no-dev --no-scripts \
    && rm composer.phar


RUN chown -R www-data:www-data \
        /var/www/storage \
        /var/www/bootstrap/cache

RUN php artisan optimize
RUN mv .env.example .env
RUN php artisan key:generate
RUN php artisan config:cache
