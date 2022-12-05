FROM php:8.0-fpm

WORKDIR /app

# Install dependencies
RUN apt-get update
RUN apt-get install -y \
    curl \
    libjpeg62-turbo \
    libonig5 \
    libpng16-16 \
    libzip4 \
    locales \
    mariadb-client \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle \
    git \
    unzip \
    zip

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions \
    exif \
    gd \
    mbstring \
    pcntl \
    pdo_mysql \
    sockets

RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

COPY --chown=www:www . /app
RUN chmod -R ug+w /app/storage

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --optimize-autoloader --no-dev

USER www

EXPOSE 9000
CMD ["php-fpm"]