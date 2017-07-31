#!/bin/bash

##############################
# This script should be on the scripts folder inside the jenkins base folder

##############################
# Shell variables needed

# Paths
PR_PATH="${JENKINS_HOME}/workspace/${JOB_NAME// /\\ }/origin/pr/${ghprbPullId}/"
COMPOSE_PATH="${PR_PATH}joomla-compose"
J_PATH="${JENKINS_HOME}/workspace/${JOB_NAME// /\\ }/origin/pr/${ghprbPullId}/merge"
REPLACE_PART1='s/${JOOMLA_PATH}/'
REPLACE_PART2='/g'

# Github info (repo and bot user info)
# (Place the valid info below regarding the repository and github account)
ORG='Organization'
REPO='Repository'
GH_USER='Username'
GH_PASS='Password'
COMMENT_MSG='A build is in progress.'
SCRIPT_PATH=${JENKINS_HOME}/IssueComment/tests/run.php


#################################################################
# Copies the Joomla! docker compose files into the PR folder
cp -R ${JENKINS_HOME}/joomla-compose ${PR_PATH}

echo "Joomla! docker compose files copied into the PR folder."

#################################################################
# Places the PHP version and path to the Joomla! pulled code
# for the PR in the docker compose file in the arguments
sed -i ${REPLACE_PART1}${J_PATH////\\/}${REPLACE_PART2} ${COMPOSE_PATH}/docker-compose.yml

sed -i 's/${PHP_VERSION}/7.1.2/g' ${COMPOSE_PATH}/docker-compose.yml

echo "Joomla! pulled code path and PHP version inserted in the docker-compose.yml file."

#################################################################
# Runs the script to post a comment in the PR
php ${SCRIPT_PATH} ${ORG} ${REPO} ${ghprbPullId} ${GH_USER} ${GH_PASS} "${COMMENT_MSG}"

echo "Posted comment in Pull Request #${ghprbPullId}."
