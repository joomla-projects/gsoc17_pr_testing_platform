FROM php:7.0-apache

# Enable Apache Rewrite Module
RUN a2enmod rewrite

# Enable MySQL root user creation without interactive input
RUN echo 'mysql-server mysql-server/root_password password root' | debconf-set-selections
RUN echo 'mysql-server mysql-server/root_password_again password root' | debconf-set-selections

# Install PHP extensions
RUN apt-get update ; \
	apt-get install -y libpng12-dev libjpeg-dev libmcrypt-dev zip unzip git libmysqlclient-dev mysql-server; \
	rm -rf /var/lib/apt/lists/* ;\
	docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr; \
	docker-php-ext-install gd; \
	docker-php-ext-configure mysqli --with-png-dir=/usr --with-jpeg-dir=/usr; \
    docker-php-ext-install mysqli

VOLUME /var/www/html

ENV JOOMLA_DB_HOST 127.0.0.1
ENV JOOMLA_DB_USER root
ENV JOOMLA_DB_PASSWORD root
ENV JOOMLA_DB_NAME joomla

# Download package and extract to web volume
RUN mkdir /usr/src/joomla; \
	git clone --depth 1 -b 3.9-dev --single-branch https://github.com/joomla/joomla-cms.git /usr/src/joomla ; \
	chown -R www-data:www-data /usr/src/joomla

# Copy init scripts and custom .htaccess
COPY docker-entrypoint.sh /entrypoint.sh
COPY makedb.php /makedb.php

RUN echo 'ServerName localhost' >> /etc/apache2/apache2.conf

EXPOSE 8080 3306

# Create Link between joomla folder and apache2 default folder
RUN ln -s -f /usr/src/joomla/* /var/www/html

ENTRYPOINT ["/entrypoint.sh"]
CMD ["apache2-foreground"]