FROM php:8.3-fpm-alpine

RUN apk add --no-cache \
    git curl libzip-dev zip unzip nodejs npm nginx bash \
    autoconf g++ make linux-headers

RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    zip \
    pcntl \
    bcmath

RUN pecl install redis \
    && docker-php-ext-enable redis \
    && apk del autoconf g++ make linux-headers

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# Сначала только composer файлы — для кеширования слоёв
COPY composer.json composer.lock* ./

# vendor здесь ещё нет (благодаря .dockerignore), ставим чисто
RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-scripts --ignore-platform-reqs

# Потом копируем весь код
COPY . .

# Теперь запускаем скрипты (package:discover и т.д.)
RUN composer dump-autoload --optimize --ignore-platform-reqs

# Vite build args — встраиваются в JS во время сборки
ARG VITE_REVERB_APP_KEY=socket-notification-key
ARG VITE_REVERB_HOST=localhost
ARG VITE_REVERB_PORT=8080
ARG VITE_REVERB_SCHEME=http

ENV VITE_REVERB_APP_KEY=$VITE_REVERB_APP_KEY
ENV VITE_REVERB_HOST=$VITE_REVERB_HOST
ENV VITE_REVERB_PORT=$VITE_REVERB_PORT
ENV VITE_REVERB_SCHEME=$VITE_REVERB_SCHEME

# Node
RUN npm ci && npm run build

RUN chown -R www-data:www-data /var/www \
    && chmod -R 755 /var/www/storage \
    && chmod -R 755 /var/www/bootstrap/cache

COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 80 8080
ENTRYPOINT ["/entrypoint.sh"]
