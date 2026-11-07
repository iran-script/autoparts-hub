FROM php:8.4-cli-bookworm
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpq-dev libicu-dev libzip-dev libonig-dev unzip git \
    && docker-php-ext-install pdo_pgsql mbstring intl bcmath opcache \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && rm -rf /var/lib/apt/lists/*
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
WORKDIR /var/www/html
COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-dev --optimize-autoloader --no-scripts
COPY . .
RUN composer dump-autoload --optimize --no-interaction
RUN chown -R www-data:www-data storage bootstrap/cache
