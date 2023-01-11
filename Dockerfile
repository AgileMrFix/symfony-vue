FROM php:8.1.4-apache

#install packages
RUN set -ex; \
        \
        apt-get update; \
        apt-get install -y \
            apt-transport-https \
            gnupg \
            libicu-dev \
            unzip \
        ; \
        rm -rf /var/lib/apt/lists/*;

#install php extensions
RUN docker-php-ext-install opcache pdo_mysql pcntl

#install intl extension
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl

#enable apache modules
RUN a2enmod headers rewrite

#install composer
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME: /tmp/composer
ENV COMPOSER_MEMORY_LIMIT: -1

RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php \
    && php /tmp/composer-setup.php --2.2 \
    && rm /tmp/composer-setup.php \
    && mv composer.phar /usr/local/bin/ \
    && ln -s /usr/local/bin/composer.phar /usr/local/bin/composer

#install the Symfony CLI
RUN curl -1sLf 'https://dl.cloudsmith.io/public/symfony/stable/setup.deb.sh' | bash \
    && apt install -y symfony-cli

COPY ./Docker/000-default.conf /etc/apache2/sites-available/

WORKDIR /var/www/html
