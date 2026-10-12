# =====================================================================
# 1) BUILD LOCAL — устанавливаем зависимости, собираем Laravel
# =====================================================================
FROM unit:php8.4 as local

# Install required packages
RUN apt-get update && apt-get install -y \
    build-essential \
    git \
    curl \
    mc \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    libgd-dev \
    jpegoptim optipng pngquant gifsicle \
    libonig-dev \
    libxml2-dev \
    zip \
    sudo \
    unzip \
    nano \
    tree \
    fish \
    libpq-dev \
    supervisor \
    cron \
    && rm -rf /var/lib/apt/lists/*


# imagick
RUN apt-get update && apt-get install -y libmagickwand-dev --no-install-recommends \
    && pecl install imagick \
    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/imagick.ini \
    && rm -rf /var/lib/apt/lists/*

# redis
RUN pecl install redis \
    && echo "extension=redis.so" > /usr/local/etc/php/conf.d/redis.ini

# xdebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug


# pdo_mysql + pdo_pgsql + gd
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo_mysql pdo_pgsql bcmath gd mbstring exif pcntl \
    && rm -rf /var/lib/apt/lists/*

# Установка FastText CLI
RUN apt-get update && apt-get install -y build-essential cmake git && \
    git clone https://github.com/facebookresearch/fastText.git /tmp/fastText && \
    cd /tmp/fastText && \
    make && \
    cp fasttext /usr/local/bin/ && \
    rm -rf /tmp/fastText

# Set work directory
WORKDIR /var/www

# Копируем проект
COPY . .

# Копируем Supervisor конфиг
COPY ./supervisord.conf /etc/supervisor/supervisord.conf

# Install composer
#ENV COMPOSER_ALLOW_SUPERUSER=1
RUN curl -sS https://getcomposer.org/installer | php -- \
    --filename=composer \
    --install-dir=/usr/local/bin

# Copy Unit configuration file
COPY ./unit.json /docker-entrypoint.d/config.json

CMD ["unitd", "--no-daemon"]