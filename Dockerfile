FROM php:8.1-fpm as php

# RUN usermod --uid 1000 www-data && groupmod --gid 1001 www-data
RUN usermod -u 1000 www-data

RUN apt-get update -y
RUN apt-get install -y unzip libpq-dev libcurl4-gnutls-dev nginx libonig-dev

RUN docker-php-ext-install mysqli pdo pdo_mysql bcmath curl opcache mbstring
# RUN pecl install xdebug && docker-php-ext-enable xdebug

COPY --from=composer /usr/bin/composer /usr/bin/composer

COPY docker/nginx/nginx.conf /etc/nginx/nginx.conf
COPY docker/php/php.ini /usr/local/etc/php/php.ini
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php/opcache.ini /usr/local/etc/php/conf.d/opcache.ini

WORKDIR /var/www

COPY --chown=www-data:www-data . .

RUN mkdir -p storage
RUN mkdir -p storage/framework
RUN mkdir -p storage/framework/{cache, views, testing, sessions}

RUN chown -R www-data .
RUN chown -R www-data storage
RUN chown -R www-data storage/logs
RUN chown -R www-data storage/framework
RUN chown -R www-data storage/framework/sessions

RUN chmod -R 755 storage
RUN chmod -R 755 bootstrap
RUN chmod -R 755 storage/logs
RUN chmod -R 755 storage/framework
RUN chmod -R 755 storage/framework/sessions

# RUN php-fpm -D
# RUN nginx -g "daemon off;"

EXPOSE 8080
ENTRYPOINT [ "docker/entrypoint.sh" ]




