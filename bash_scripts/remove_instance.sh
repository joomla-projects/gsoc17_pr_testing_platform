#!/usr/bin/env bash

##########################
# This script will be located on the devilbox base folder

INSTANCE_ID=23456
DOMAIN='dbox-tests'

INSTANCE_FOLDER=${INSTANCE_ID}.${DOMAIN}
HOST_INSTANCE_FOLDER=data/www/${INSTANCE_FOLDER}
DB_PASSWORD='place password here'
DB_NAME="joomla-${INSTANCE_ID}"

docker-compose exec -d --user root php env TERM=xterm /bin/sh -c "
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 -e 'DROP DATABASE \`${DB_NAME}\`;';
"

echo "Database ${DB_NAME} for this instance dropped."

rm -R ${HOST_INSTANCE_FOLDER}

echo "Joomla instance folder in data/www/ removed."

