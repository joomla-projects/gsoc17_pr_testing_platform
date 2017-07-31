#!/usr/bin/env bash

##########################
# This script will be located on the devilbox base folder

INSTANCE_ID=23456
DOMAIN='dbox-tests'
#REPOSITORY='repository'
#PR_ID='id'
INSTANCE_FOLDER=${INSTANCE_ID}.${DOMAIN}
HOST_INSTANCE_FOLDER=data/www/${INSTANCE_FOLDER}/htdocs
DB_PASSWORD='place password here'
DB_NAME="joomla-${INSTANCE_ID}"

mkdir data/www/${INSTANCE_FOLDER}
mkdir data/www/${INSTANCE_FOLDER}/htdocs

# cp -r /var/lib/jenkins/workspace/${REPOSITORY}/origin/pr/${PR_ID}/merge/. data/www/${INSTANCE_ID}.${DOMAIN}/htdocs
git clone --depth 1 -b staging --single-branch https://github.com/joomla/joomla-cms.git ${HOST_INSTANCE_FOLDER}

echo "Joomla PR code placed in the instance folder in data/www/ folder."

cp files/users.sql ${HOST_INSTANCE_FOLDER}/installation/sql/mysql

cp files/configuration.php ${HOST_INSTANCE_FOLDER}

sed -i 's/#__/jos_/g' ${HOST_INSTANCE_FOLDER}/installation/sql/mysql/joomla.sql
sed -i 's/#__/jos_/g' ${HOST_INSTANCE_FOLDER}/installation/sql/mysql/sample_testing.sql
sed -i 's/#__/jos_/g' ${HOST_INSTANCE_FOLDER}/installation/sql/mysql/users.sql

sed -i "s/\${DBPASSWORD}/${DB_PASSWORD}/g" ${HOST_INSTANCE_FOLDER}/configuration.php
sed -i "s/\${DBNAME}/${DB_NAME}/g" ${HOST_INSTANCE_FOLDER}/configuration.php
sed -i "s/\${INSTANCEFOLDER}/${INSTANCE_FOLDER}/g" ${HOST_INSTANCE_FOLDER}/configuration.php

echo "Configuration file generated in the Joomla instance base folder."

docker-compose exec -d --user root php env TERM=xterm /bin/sh -c "cd /shared/httpd;
chown -R devilbox:devilbox ${INSTANCE_FOLDER}/;
chmod -R 755 ${INSTANCE_FOLDER}/;
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 -e 'CREATE DATABASE \`${DB_NAME}\`;';

mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < ${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/joomla.sql;
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < ${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/sample_testing.sql;
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < ${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/users.sql ;
"

echo "Database ${DB_NAME} and tables created, and data imported to the database."