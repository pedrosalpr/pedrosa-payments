# Image
FROM php:8.3-fpm-alpine3.19

ARG CONTAINER_ENVIRONMENT
ARG CONTAINER_TIMEZONE
ARG USER_UID=1000
ARG USER_GID=$USER_UID

ENV DOCUMENT_ROOT=/var/www/html
ENV USER=pedrosa

ENV CONTAINER_ENVIRONMENT=${CONTAINER_ENVIRONMENT}
ENV TZ=${CONTAINER_TIMEZONE:-UTC}

WORKDIR ${DOCUMENT_ROOT}

# Create system user to run Composer and Artisan Commands and Set Timezone
RUN echo ${TZ} >> /etc/timezone \
    && addgroup --gid $USER_GID -S ${USER} \
    && adduser -u $USER_UID -D -S -G ${USER} ${USER}

# Install dependencies
RUN apk update && apk upgrade --no-cache \
    && apk add --no-cache \
    pkgconfig icu-dev $PHPIZE_DEPS linux-headers \
    libxml2-dev \
    libxslt-dev \
    libmcrypt-dev \
    libgcrypt \
    nginx \
    git \
    && ln -s /usr/lib/libgcrypt.so.20 /usr/lib/libgcrypt.so \
    && pecl channel-update pecl.php.net \
    && pecl install redis-6.0.2 \
    && docker-php-ext-enable opcache redis \
    && docker-php-ext-install pdo_mysql intl exif pcntl bcmath ctype \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy configuration files
COPY .setup/nginx/nginx.conf /etc/nginx/nginx.conf
COPY .setup/nginx/sites-available /etc/nginx/sites-available
COPY .setup/nginx/bad-user-agents.rules /etc/nginx/baduseragent.rules
COPY .setup/php/php.ini /usr/local/etc/php/php.ini
COPY .setup/php/dev/php.ini /usr/local/etc/php/conf.d/php-dev.ini
COPY .setup/php/php-fpm-www.conf /usr/local/etc/php-fpm.d/www.conf
COPY .setup/php/dev/xdebug.ini /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini-disabled
COPY .setup/scripts/entrypoint.sh /entrypoint.sh

# Create Files and Folders
RUN touch /var/run/nginx.pid \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \
    && mkdir /etc/nginx/sites-enabled \
        ${DOCUMENT_ROOT}/public \
    && echo "<?php phpinfo();" >> ${DOCUMENT_ROOT}/public/index.php \
    && chown -R ${USER}:${USER} /var/run/nginx.pid \
        /var/lib/nginx \
        /var/log/nginx \
        /etc/nginx/sites-enabled \
        ${DOCUMENT_ROOT} \
    && chmod +x /entrypoint.sh \
    && rm -rf .setup 

# start the application
CMD ["nginx", "-g", "daemon off;"]
ENTRYPOINT ["sh", "/entrypoint.sh"]

EXPOSE 8080

# Install dependencies
RUN if [[ "$CONTAINER_ENVIRONMENT" == "local" ]] ; then \
        pecl install xdebug-3.3.1 \
        && rm /usr/local/etc/php/php.ini  \
        && ln -s /usr/local/etc/php/conf.d/php-dev.ini /usr/local/etc/php/php.ini \
        && mv /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini-disabled /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
        && docker-php-ext-enable xdebug \
        && rm -rf /var/cache/apk/* \
    ; fi

# Add application
COPY --chown=${USER}:${USER} . .

# Fix permissions
RUN chmod 775 -R storage bootstrap/cache

USER ${USER}

# Run composer
RUN if [[ "$CONTAINER_ENVIRONMENT" == "production" ]] ; then \
        composer install --no-cache \
            --ignore-platform-reqs \
            --no-interaction \
            --no-plugins \
            --no-scripts \
            --prefer-dist \
            --no-dev \
            --optimize-autoloader -n \
        && composer dump-autoload \
    ; else \
        composer install --no-cache \
            --ignore-platform-reqs \
            --no-interaction \
            --prefer-dist \
            --optimize-autoloader -n \
        && composer dump-autoload \
    ; fi

HEALTHCHECK CMD curl --fail http://localhost:8080/api/health/ -A "docker-healthcheck" || exit 1
