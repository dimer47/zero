FROM php:8.3-cli-alpine

LABEL maintainer="Laravel Zero Community"

ARG ZEROGROUP=1000
ARG ZEROUSER=1000

WORKDIR /var/www/html

ENV TZ=UTC

# Install essential system dependencies for Laravel Zero
RUN apk add --no-cache \
    bash \
    git \
    unzip \
    zip \
    curl \
    shadow \
    su-exec \
    jq \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    linux-headers

# Install required PHP extensions for Laravel Zero
RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    zip \
    mbstring \
    intl \
    pcntl \
    bcmath

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Create the zero user
RUN delgroup dialout \
    && addgroup -g ${ZEROGROUP} zero \
    && adduser -D -u ${ZEROUSER} -G zero -h /home/zero -s /bin/bash zero \
    && mkdir -p /home/zero/.composer \
    && chown -R zero:zero /home/zero

# Copy the startup script
COPY start-container /usr/local/bin/start-container
RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]
