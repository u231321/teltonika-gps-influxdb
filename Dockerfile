FROM php:7-cli-alpine
LABEL maintainer="Timo Taskinen <timo.taskinen@iki.fi>"

# Install tools
RUN apk add --update \
  supervisor \
  bash \
  nano

# Install PHP dependencies
RUN docker-php-ext-install bcmath

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy app into image
COPY composer.json /usr/src/app/
COPY ./src /usr/src/app/src/
COPY importer.php /usr/src/app/
WORKDIR /usr/src/app

# Install dependencies
RUN composer install

# Dump autoload
RUN composer dump-autoload -o

# Configure supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
ENTRYPOINT ["/usr/bin/supervisord","-c","/etc/supervisor/conf.d/supervisord.conf"]

# Copy environment configuration
COPY .env /usr/src/app/
