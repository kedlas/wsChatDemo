FROM php:7.1.6

COPY . /app

WORKDIR /app

ENV COMPOSER_VERSION 1.1.2

RUN apt-get -y update && \
    apt-get -y install git curl

#RUN php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer
#
#RUN composer install

EXPOSE 8080

CMD ["php", "src/index.php"]