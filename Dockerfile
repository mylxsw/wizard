FROM php:7.3-apache

WORKDIR /webroot
ENV APACHE_DOCUMENT_ROOT /webroot/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

RUN echo "memory_limit=-1" > "$PHP_INI_DIR/conf.d/memory-limit.ini" \
    && echo "date.timezone=${PHP_TIMEZONE:-UTC}" > "$PHP_INI_DIR/conf.d/date_timezone.ini" \
    && echo "upload_max_filesize = 100M\npost_max_size = 0" > "$PHP_INI_DIR/conf.d/upload-limit.ini"

RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmcrypt-dev \
        libpng-dev \
        libzip-dev \
        libldap-dev \
        wget \
        curl \
        git \
        subversion \
        zip \
        unzip \
        mercurial \
        --no-install-recommends && rm -r /var/lib/apt/lists/* \
    && docker-php-ext-install -j$(nproc) pcntl exif pdo_mysql zip ldap \
    && docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd
RUN wget https://getcomposer.org/download/1.6.4/composer.phar \
    && mv composer.phar /usr/bin/composer.phar \
    && chmod +x /usr/bin/composer.phar \
    && ln -s /usr/bin/composer.phar /usr/bin/composer \
    && php /usr/bin/composer config -g repo.packagist composer https://packagist.laravel-china.org

RUN a2enmod rewrite
RUN a2enmod headers

COPY ./composer.lock ./composer.json /webroot/
RUN php /usr/bin/composer install --prefer-dist --no-autoloader --no-scripts --no-dev

COPY ./ /webroot

RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

RUN cp .env.docker .env \
    && php /usr/bin/composer dump-autoload --optimize \
    && chown www-data:www-data -R ./ \
    && php artisan storage:link
