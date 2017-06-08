FROM php:7.1-apache

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Install PHP extensions
RUN apt-get update ; apt-get install -y libpng12-dev libjpeg-dev libmcrypt-dev zip unzip git; \ rm -rf /var/lib/apt/lists/* ;\
	docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
	docker-php-ext-install gd

VOLUME /var/www/html

# Download package and extract to web volume
RUN mkdir /usr/src/joomla; \
	git clone --depth 1 -b 4.0-dev --single-branch https://github.com/joomla/joomla-cms.git /usr/src/joomla ; \
	chown -R www-data:www-data /usr/src/joomla

# Copy init scripts and custom .htaccess
COPY docker-entrypoint.sh /entrypoint.sh
COPY makedb.php /makedb.php

RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]