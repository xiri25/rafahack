FROM php:8.2-apache
ARG DEBIAN_FRONTEND=noninteractive
RUN docker-php-ext-install mysqli pdo pdo_mysql 
RUN apt-get update \
    && apt-get install -y apt-utils \
    && apt-get install -y sendmail libpng-dev \
    && apt-get install -y libzip-dev \
    && apt-get install -y zlib1g-dev \
    && apt-get install -y zip \
    && apt-get install -y libonig-dev \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install mbstring
RUN docker-php-ext-install zip
RUN docker-php-ext-install gd

RUN a2enmod rewrite