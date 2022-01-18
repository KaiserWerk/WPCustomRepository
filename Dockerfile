FROM php:7.4-apache

RUN apt-get update
RUN apt-get install -y git
#RUN docker-php-ext-install mysqli
#RUN docker-php-ext-install json
#RUN docker-php-ext-install zip
#RUN docker-php-ext-install xml
#RUN docker-php-ext-install curl
#RUN docker-php-ext-install pdo
#RUN docker-php-ext-install pdo_mysql
#RUN docker-php-ext-install session

RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer

COPY . /var/www/html

COPY ./docker/vhost.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

RUN mkdir -p /var/www/html/var/sessions
RUN chmod -R 0777 /var/www/html/var
RUN chown -R www-data:www-data /var/www/html
RUN a2enmod rewrite

RUN composer update

RUN service apache2 restart

EXPOSE 80
