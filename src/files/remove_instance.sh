#!/usr/bin/env bash

##########################
# This script will be located on the files folder

PHP_VERSION=$1
INSTANCE_ID=$2

DOMAIN="domain"
DB_PASSWORD="password"
DB_NAME="joomla-${INSTANCE_ID}"
INSTANCE_FOLDER=${INSTANCE_ID}.${DOMAIN}

mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 -e "DROP DATABASE \`${DB_NAME}\`;"

echo "Database ${DB_NAME} for this instance dropped."

rm -R httpd/${INSTANCE_FOLDER}

echo "Joomla instance folder in the php${PHP_VERSION} container removed."
