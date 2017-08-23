#!/usr/bin/env bash

##########################
# This script will be located on the files folder

PR_ID=$1
PHP_VERSION=$2
INSTANCE_ID=$3

DOMAIN="domain"
REPOSITORY="repository"
DB_PASSWORD="password"
INSTANCE_FOLDER=${INSTANCE_ID}.${DOMAIN}


####################################
# Creates folder for joomla instance

mkdir httpd/${INSTANCE_FOLDER}/
mkdir httpd/${INSTANCE_FOLDER}/htdocs

####################################################################
# Copies the PR code from jenkins workspace into the instance folder

cp -r /var/jenkins/workspace/${REPOSITORY}/origin/pr/${PR_ID}/merge/. httpd/${INSTANCE_FOLDER}/htdocs
#git clone --depth 1 -b 4.0-dev --single-branch https://github.com/joomla/joomla-cms.git ${INSTANCE_FOLDER}/htdocs

echo "Joomla PR code placed in the instance folder in the php${PHP_VERSION} container."

cp files/users.sql httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql

cp files/configuration.php httpd/${INSTANCE_FOLDER}/htdocs

sed -i 's/#__/jos_/g' httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/joomla.sql
sed -i 's/#__/jos_/g' httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/sample_testing.sql
sed -i 's/#__/jos_/g' httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/users.sql

####################################################################
# Places DB info and instance path in the joomla conf file

sed -i "s/\${DBPASSWORD}/${DB_PASSWORD}/g" httpd/${INSTANCE_FOLDER}/htdocs/configuration.php
sed -i "s/\${DBNAME}/${DB_NAME}/g" httpd/${INSTANCE_FOLDER}/htdocs/configuration.php
sed -i "s/\${INSTANCEFOLDER}/${INSTANCE_FOLDER}/g" httpd/${INSTANCE_FOLDER}/htdocs/configuration.php

echo "Configuration file generated in the Joomla instance base folder."

##################################################
# Sets up folder permissions and the DB

chown -R devilbox:devilbox httpd/${INSTANCE_FOLDER}/
chmod -R 755 httpd/${INSTANCE_FOLDER}/

mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 -e "CREATE DATABASE \`${DB_NAME}\`;"

mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/joomla.sql
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/sample_testing.sql
mysql --user=root --password=${DB_PASSWORD} --host=127.0.0.1 ${DB_NAME} < httpd/${INSTANCE_FOLDER}/htdocs/installation/sql/mysql/users.sql

echo "Database ${DB_NAME} and tables created, and data imported to the database."
