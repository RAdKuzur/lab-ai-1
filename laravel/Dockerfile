FROM php:8.4-cli

RUN apt-get update && apt-get install -y \
    git \
    curl \
    libzip-dev \
    zip \
    unzip \
    libpq-dev \
    libpng-dev \
    && docker-php-ext-install \
    pdo \
    pdo_pgsql \
    zip \
    gd \
    opcache \
    sockets \
    pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --prefer-dist --no-interaction --no-progress

RUN mkdir -p bootstrap/cache storage/logs storage/framework/sessions \
    storage/framework/views storage/framework/cache \
    && chmod -R 775 storage bootstrap/cache
EXPOSE 9000

# Генерация ключа и запуск сервера
CMD php artisan key:generate && php artisan serve --host=0.0.0.0 --port=9000
