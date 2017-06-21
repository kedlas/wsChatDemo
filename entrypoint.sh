#!/usr/bin/env bash

# Download composer
php -r "readfile('https://getcomposer.org/installer');" | php -- --install-dir=/usr/local/bin --filename=composer
# Install dependencies
composer global require "hirak/prestissimo:^0.3"
composer install --no-interaction

php /app/src/index.php