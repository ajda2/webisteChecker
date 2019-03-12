FROM php:7.3-apache

RUN docker-php-ext-install pdo_mysql \
&& a2enmod rewrite

COPY docker/php-apache/php.ini /usr/local/etc/php/

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get update -y && apt-get install -y -qq git \
	libpng-dev\
	zlib1g-dev \
	libjpeg62-turbo-dev \
	apt-transport-https \
	libfreetype6-dev \
	libmcrypt-dev \
	libssl-dev \
	zip unzip \
	wget \
	ssmtp \
	vim

RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/
RUN docker-php-ext-install -j$(nproc) iconv gd bcmath

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug
COPY docker/php-apache/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

# SMTP configuration MailHog
RUN echo 'mailhub=mailhog:1025 \nFromLineOverride=YES \nUseTLS=NO' >> /etc/ssmtp/ssmtp.conf