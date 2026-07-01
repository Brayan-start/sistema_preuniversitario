FROM richarvey/nginx-php-fpm:latest

LABEL maintainer="UPEA - Sistema Inscripciones"

COPY . /var/www/html

RUN rm -f .env

ENV SKIP_COMPOSER=1
ENV WEBROOT=/var/www/html/public
ENV PHP_ERRORS_STDERR=1
ENV RUN_SCRIPTS=1
ENV REAL_IP_HEADER=1
ENV LOG_CHANNEL=stderr

RUN if command -v apk >/dev/null 2>&1; then \
        apk add --no-cache postgresql-dev \
        && docker-php-ext-install pdo_pgsql pgsql; \
    elif command -v apt-get >/dev/null 2>&1; then \
        apt-get update && apt-get install -y libpq-dev \
        && docker-php-ext-install pdo_pgsql pgsql; \
    fi

RUN echo "memory_limit = 256M" > /usr/local/etc/php/conf.d/memory.ini

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
RUN APP_URL=http://localhost composer install --no-dev --optimize-autoloader --no-interaction

RUN mkdir -p /var/www/html/storage/framework/views \
    && mkdir -p /var/www/html/storage/framework/cache \
    && mkdir -p /var/www/html/storage/framework/sessions \
    && mkdir -p /var/www/html/storage/logs \
    && chmod -R 777 /var/www/html/storage \
    && chmod -R 777 /var/www/html/bootstrap/cache

COPY nginx.conf /etc/nginx/sites-enabled/default.conf

EXPOSE 8080