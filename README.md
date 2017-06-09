# GSoC 2017 Joomla PR Testing Platform

## What is this?
---------------------
* This is a platform meant for allowing users to test PRs more easily without much effort or knowledge to setup the Joomla instance for the PR to be tested
* The idea would be to have a website with github authentication where the user can request his desired environment to test the PR. 
* The user will be presented with options to configure the test environment for the intended test request with PHP versions and the joomla cms branches. Then the user is redirected to the Joomla! Instance with the PatchTester view opened to apply the desired patch and test right away.

## Installing
---------------------

### Install/update Dependencies with Composer

```
composer install  or  composer update
```
