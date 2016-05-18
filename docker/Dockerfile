# Based on https://github.com/docker-library/drupal/

from php:5.6-apache

run a2enmod rewrite

run apt-get update && apt-get install -y libpng12-dev libjpeg-dev libpq-dev git vim \
	&& rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
	&& docker-php-ext-install gd mbstring opcache pdo pdo_mysql pdo_pgsql

run { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=60'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini
	
run { \
		echo 'upload_max_filesize=10M'; \
		echo 'post_max_size=10M'; \
		echo 'date.timezone=UTC'; \
	} > /usr/local/etc/php/conf.d/php-conf.ini

workdir /var/www/html

env DRUPAL_VERSION 8.1.1
env DRUPAL_MD5 529f3d72964c612695f68e0a6078b8ae

run curl -fSL "http://ftp.drupal.org/files/projects/drupal-${DRUPAL_VERSION}.tar.gz" -o drupal.tar.gz \
	&& echo "${DRUPAL_MD5} *drupal.tar.gz" | md5sum -c - \
	&& tar -xz --strip-components=1 -f drupal.tar.gz \
	&& rm drupal.tar.gz \
	&& chown -R www-data:www-data sites

copy cp-init /usr/local/bin/
cmd ["cp-init"]
