FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN composer install

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
