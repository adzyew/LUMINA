
# Stage 1: Build frontend assets
FROM node:22-bookworm-slim AS frontend

WORKDIR /app

COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build


# Stage 2: Install PHP dependencies
FROM php:8.3-cli-bookworm AS composer-deps

RUN apt-get update && apt-get install -y --no-install-recommends \
    git unzip libicu-dev libzip-dev libpng-dev \
    libjpeg62-turbo-dev libfreetype6-dev \
    libxml2-dev libonig-dev \
    && docker-php-ext-configure gd \
       --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
       bcmath exif gd intl mbstring pdo_mysql \
       pcntl opcache zip \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-progress \
    --optimize-autoloader \
    --no-scripts

COPY . .
RUN composer dump-autoload \
    --no-dev --optimize --no-scripts


# Stage 3: Production runtime
FROM php:8.3-fpm-bookworm AS production

ENV APP_ENV=production
ENV APP_DEBUG=false

RUN apt-get update && apt-get install -y --no-install-recommends \
    nginx supervisor \
    libicu72 libzip4 libpng16-16 \
    libjpeg62-turbo libfreetype6 \
    libxml2 libonig5 \
    && docker-php-ext-configure gd \
       --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
       bcmath exif gd intl mbstring pdo_mysql \
       pcntl opcache zip \
    && rm -rf /var/lib/apt/lists/* \
    && mkdir -p /run/nginx /var/log/supervisor

WORKDIR /var/www/html

COPY --from=composer-deps /var/www/html /var/www/html
COPY --from=frontend /app/public/build /var/www/html/public/build

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh

RUN chmod +x /usr/local/bin/entrypoint.sh \
    && mkdir -p storage/framework/cache \
       storage/framework/sessions \
       storage/framework/views \
       storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && nginx -t

EXPOSE 10000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
