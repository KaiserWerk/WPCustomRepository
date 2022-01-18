FROM php:7.4-apache

RUN apt-get update
RUN apt-get install -y git
RUN docker-php-ext-install pdo_mysql

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

COPY ./vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/var/sessions
RUN mkdir -p /var/www/html/var/logs

RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite

RUN composer update

RUN service apache2 restart

EXPOSE 80
