FROM php:8.2-fpm AS php-builder

WORKDIR /app

RUN apt-get update
RUN apt-get update && apt-get install -y \
    libzip-dev \
    locales \
    zip \
    unzip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY . /app

RUN composer install --no-dev

#---------------------------------------------------------#

FROM node:19.1.0 AS node-builder

WORKDIR /app

COPY package-lock.json /app
COPY package.json /app
RUN npm ci

COPY webpack.config.js /app
COPY babel.config.json /app
COPY tsconfig.json /app
COPY resources/js /app/resources/js
COPY resources/sass /app/resources/sass

USER root
RUN npm run prod

#---------------------------------------------------------#

FROM php:8.2-fpm

ARG UID=1000
ARG GID=1000
ARG PORT=8080

WORKDIR /app

RUN apt-get update
RUN apt-get install -y \
    curl \
    nginx \
    sudo \
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

RUN groupadd -g $GID www
RUN useradd -Ms /bin/bash -u $UID -g www www
RUN usermod -aG www-data www

COPY ./docker/nginx/app.conf /etc/nginx/sites-enabled/default
RUN sed -i s/\$\{PORT\}/$PORT/ /etc/nginx/sites-enabled/default

COPY --chown=www:www-data . /app
COPY --chown=www:www-data --from=node-builder /app/public/js /app/public/js
COPY --chown=www:www-data --from=node-builder /app/public/css /app/public/css
COPY --chown=www:www-data --from=php-builder /app/storage /app/storage
COPY --chown=www:www-data --from=php-builder /app/vendor /app/vendor

RUN chmod -R ug+w /app/storage

RUN echo "www ALL=(ALL) NOPASSWD: /usr/sbin/service nginx start" >> /etc/sudoers
RUN echo "www ALL=(ALL) NOPASSWD: /usr/sbin/service nginx stop" >> /etc/sudoers

USER www

EXPOSE $PORT
ENTRYPOINT ["bash", "-c", "sudo service nginx start && php-fpm"]
