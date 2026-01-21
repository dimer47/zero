FROM php:8.2-cli-alpine

LABEL maintainer="Laravel Zero Community"

ARG ZEROGROUP=1000
ARG ZEROUSER=1000

WORKDIR /var/www/html

ENV TZ=UTC

# Installer les dépendances système essentielles pour Laravel Zero
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    curl \
    shadow \
    su-exec \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers

# Installer les extensions PHP requises pour Laravel Zero
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    mbstring \
    intl \
    pcntl \
    bcmath

# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Créer l'utilisateur zero
RUN delgroup dialout \
    && addgroup -g ${ZEROGROUP} zero \
    && adduser -D -u ${ZEROUSER} -G zero -h /home/zero -s /bin/bash zero \
    && mkdir -p /home/zero/.composer \
    && chown -R zero:zero /home/zero

# Copier le script de démarrage
COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]
CMD ["php", "artisan"]
