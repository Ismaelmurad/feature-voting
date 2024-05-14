FROM php:8.1-fpm-buster

# Update and install packages
RUN apt-get update && apt-get install -y \
    apt-utils \
    acl \
    curl \
    zlib1g-dev \
    libzip-dev \
    libicu-dev \
    libmagickwand-dev \
    libpng-dev \
    libpng16-16 \
    libjpeg-dev \
    libjpeg-turbo-progs \
    libjpeg62-turbo-dev \
    libjpeg62-turbo \
    zip \
    tzdata \
    iproute2 \
    locales \
    libpng-dev \
    libfreetype6 \
    libfreetype6-dev \
    libjpeg-dev \
    libwebp-dev \
    libjpeg-turbo-progs \
    libc6 \
    xfonts-75dpi \
    xfonts-base \
    libc6 \
    zlibc \
    libc-client-dev \
    libkrb5-dev

# Configure PHP Extensions
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg

# Install PHP Extensions
RUN docker-php-ext-install -j$(nproc) \
    mysqli \
    pdo \
    pdo_mysql \
    zip \
    opcache \
    intl \
    gd \
    bcmath \
    xml

RUN pecl install \
    apcu

# Enable PHP Extensions
RUN docker-php-ext-enable \
    apcu \
    xml

# Cleanup storage
RUN apt-get clean \
	&& rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Set timezone
RUN ln -sf /usr/share/zoneinfo/UTC /etc/localtime
RUN dpkg-reconfigure --frontend noninteractive tzdata

# Change uid and gid to docker user uid/gid
RUN usermod -u 1000 www-data \
    && groupmod -g 1000 www-data

WORKDIR /var/www/html

EXPOSE 9000
