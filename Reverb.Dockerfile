FROM php:8.4-cli

# Устанавливаем зависимости
RUN apt-get update && apt-get install -y \
    zip unzip git curl libpq-dev libonig-dev \
    && rm -rf /var/lib/apt/lists/*

# PHP расширения
RUN pecl install redis \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

RUN docker-php-ext-install pdo_mysql pdo_pgsql bcmath mbstring exif pcntl

# Рабочая директория
WORKDIR /var/www
COPY laravel .

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer --install-dir=/usr/local/bin

RUN composer install --no-dev --optimize-autoloader

# Команда запуска Reverb
CMD ["php", "artisan", "reverb:start", "--host=0.0.0.0", "--port=8080"]
