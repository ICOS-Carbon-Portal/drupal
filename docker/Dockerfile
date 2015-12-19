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
	} > /usr/local/etc/php/conf.d/php-conf.ini

workdir /var/www/html

env DRUPAL_VERSION 8.0.0
env DRUPAL_MD5 92ce9a54fa926b58032a4e39b0f9a9f1

run curl -fSL "http://ftp.drupal.org/files/projects/drupal-${DRUPAL_VERSION}.tar.gz" -o drupal.tar.gz \
	&& echo "${DRUPAL_MD5} *drupal.tar.gz" | md5sum -c - \
	&& tar -xz --strip-components=1 -f drupal.tar.gz \
	&& rm drupal.tar.gz \
	&& chown -R www-data:www-data sites

copy /conf/settings.php /var/www/html/sites/default/settings.php
copy /conf/services.yml /var/www/html/sites/default/services.yml

run chown www-data:www-data -R /var/www/html/sites/ \
	&& chmod 664 /var/www/html/sites/default/settings.php \
	&& ln -s /drupal_files/themes/ /var/www/html/themes/custom \
	&& ln -s /drupal_files/modules/ /var/www/html/modules/custom \
	&& mkdir /var/www/html/sites/default/files \
	&& chown www-data:www-data -R /var/www/html/sites/default/files	
