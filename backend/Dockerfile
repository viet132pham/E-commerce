FROM php:8.3-cli

RUN apt-get update \
    && apt-get install -y git unzip libzip-dev libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app

COPY composer.json composer.lock ./
RUN if [ -f composer.lock ]; then composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader; else composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader; fi

COPY . .

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
