#  GSoC 2017 Joomla PR Testing Platform
![JGSoC](http://sempreupdate.com.br/wp-content/uploads/2016/06/gsoc-joomla-2016.jpg)

## What is this?
---------------------
* This is a platform meant for allowing users to test PRs more easily without much effort or knowledge to setup the Joomla instance for the PR to be tested
* The idea would be to have a website with github authentication where the user can request his desired environment to test the PR. 
* The user will be presented with options to configure the test environment for the intended test request with PHP versions. After requesting the test the user is redirected to the working Joomla! instance ready for testing right away.

The platform is composed of 3 parts, the [Jenkins](https://jenkins.io/) setup, the multi-container setup with [Devilbox](https://github.com/cytopia/devilbox/) and the website for the tests requests.

## Jenkins setup

![Jenkins](https://raw.githubusercontent.com/docker-library/docs/3ab4dafb41dd0e959ff9322b3c50af2519af6d85/jenkins/logo.png)

Jenkins's role here is to be our connection to the Github API and the repository, it's intended to build any incoming Pull Requests / Commits and allow to post comments on the PR. Jenkins is a cross platform CI tool for automation with a large base of plugins which support building, deployment and automation of any project.

So for this project the [Github Pull Request builder plugin](https://wiki.jenkins.io/display/JENKINS/GitHub+pull+request+builder+plugin) is used in order to build pull requests.

We are going to run builds in Jenkins server based on a set of events like creating a PR, pushing commits, commenting PRs, etc. A Jenkins project will be created to run these builds for Github pull request events like create, push, comment, close and reopen.

First step is installing Jenkins on a local or remote server.

### 1. Installation

Note: The tests were made in a Debian based machine so the examples will be presented taking that into account, but it is almost the same for other distributions. 

First of all, in order to test this a Github repository will be needed for testing purposes so a new one should be created for this.

There are two different approaches here, either setup Jenkins in the host machine or setup Jenkins with Docker. For this project so far Jenkins was setup directly in a host machine.

### 1.1 Setup Jenkins on host

#### 1.1.1 Web server

Before installing Jenkins the first thing to install should be our web server if it isn't installed yet, either Apache or Nginx:
```
$ sudo apt update
$ sudo apt install apache2
```
or
```
$ sudo apt update
$ sudo apt install nginx
```
 and afterwards check if it is properly setup on ```http://localhost```.

#### 1.1.2 Git

Next install Git if not already installed:
```
$ sudo apt update
$ sudo apt install git
$ git config --global user.name "username"
$ git config --global user.email "email@email.com"
```
#### 1.1.3 JDK and JRE

Before installing Jenkins itself make sure JDK and JRE are installed:
```
$ sudo apt install openjdk-8-jre
$ sudo apt install openjdk-8-jdk
```
#### 1.1.4 Jenkins

Now to install Jenkins just run:

```
$ wget -q -O - https://pkg.jenkins.io/debian/jenkins-ci.org.key | sudo apt-key add -
$ sudo sh -c 'echo deb http://pkg.jenkins.io/debian-stable binary/ > /etc/apt/sources.list.d/jenkins.list'
$ sudo apt update
$ sudo apt install jenkins
```
and should you want to upgrade/change its version just download the war file, replace on the ```/usr/lib/jenkins/``` folder and restart the jenkins service:

```
$ wget https://updates.jenkins-ci.org/download/war/jenkins.war
$ cp /usr/lib/jenkins/jenkins.war /usr/lib/jenkins/jenkins.war.previous.version
$ cp jenkins.war /usr/lib/jenkins/
$ systemctl stop jenkins
$ systemctl start jenkins
```
#### 1.1.5 Jenkins GUI setup

Depending if you are testing locally or remotely head over to ```http://localhost:8080``` or ```http://your.domain.com:8080``` to start the GUI setup. 

Should you choose to register a free domain go to a website like Freenom, register the domain and add DNS records for the domain, specific subdomains and wildcard or you can use Digital Ocean.

For this test we'll consider locally. To unlock Jenkins follow the instructions given on the GUI after accessing on the browser. Get the initial password from the Jenkins folder:
```
$ sudo cat /var/lib/jenkins/secrets/initialAdminPassword
```
Select "Install Suggested Plugins" to install the default plugins, then either create a new user or continue as admin logging in with username ```admin``` and password ```ab56a379e3154a38acfc46b91bc0c25a``` and change it by clicking the "admin" tab on the right hand side of screen and "Configure" option in the menu. 

Afterwards head to Manage Jenkins -> Configure Global Security and select the "Safe HTML" option under "Markup Formatter" section. Without this, the "Build History" section of the Jenkins jobs won't render HTML links.

#### 1.1.6 Install plugins

Go to ```http://localhost:8080```, then to Manage Jenkins -> Manage Plugins and check if the "Git plugin", "GitHub plugin", "GitHub Pull Request Builder" and "Rebuilder" plugins are or are not installed, if not find and tick those plugins for installation, click the "Install without restart" button and restart Jenkins with ```$ sudo service jenkins restart```.

#### 1.1.7 Configure Jenkins globally

Head over to ```http://localhost:8080```, then to Manage Jenkins -> Configure System and here, at this stage, if you have a domain and URL to access Jenkins from outside add it to the "Jenkins URL override" field, if you're testing locally use the [Ngrok](https://ngrok.com/) tool. 

Ngrok is tool which creates a secure tunnel to localhost expose a local server to the internet allowing Github and Jenkins to communicate properly while testing locally. To use it just download ngrok and after unziping run ```$ ./ngrok http 80``` and leave it running, then to access localhost through ngrok just use the provided url ```http://xxxxxxxx.ngrok.io/```.

Place the generated url ```http://xxxxxxxx.ngrok.io/``` in the "Jenkins URL override" field and place it as well in the "Jenkins URL" field in "Jenkins Location" in the settings.

Next click the "Add" button located next to the select box associated with "Credentials" label, select "Username with password" as "Kind" field, type in the github credentials on the "Username" and "Password" fields and add the username into the "Admin list" textbox in order to be an allowed admin user. 

#### 1.1.8 Configure Github web hooks

At this point it will be necessary to configure the Github webhooks which will listen for the events we need for building the repository. 

First thing will be to obtain the web hook URL. Go to ```http://xxxxxxxx.ngrok.io/```, Manage Jenkins -> Configure System and under the "GitHub" section, click "Advanced" button, tick "Specify another hook url for GitHub configuration" tickbox and obtain the URL ```http://xxxxxxxx.ngrok.io/github-webhook/```, then untick it again and exit settings without saving. 

After obtaining the URL next step is to enable the web hook URL in Github. Head to the Github repository for this test, click "Settings" tab, "Webhooks" menu option, "Add webhook" button and type ```http://xxxxxxxx.ngrok.io/ghprbhook/``` into the "Payload URL" field, select ```x-www-form-urlencoded``` in "Content Type" field, tick "Let me select individual events." option and tick just ```Issue comment```, ```Pull request``` and ```Push``` options and click "Add webhook" button.

If you see a green tick icon next to the webhook URL after refreshing the page it means that the link is working. 

Final step missing is enabling the Jenkins (Github) plugin service. 

Head to the Github repository, click the "Settings" tab, click "Integrations & services" menu option, click "Add service" button and find Jenkins (GitHub plugin) service and type the web hook URL that was obtained in the beginning (```http://xxxxxxxx.ngrok.io/github-webhook/```) into the "Payload URL" field and click the "Add service" button.

#### 1.1.9 Create Jenkins project

Go to ```http://xxxxxxxx.ngrok.io/```, click on "New item", type a name for the project (it can be the name of the repository for example) in the "Enter an item name" field, select "Freestyle project" option and click "OK" to finish.

#### 1.1.10 Configure Jenkins project

Go to ```http://xxxxxxxx.ngrok.io/```, click on the link for the job that was created in the list, click "Configure" and in the "General" tab tick "GitHub project" option, type the Github repository URL in "Project URL" field and tick "This project is parameterized" option. This will change default "Build Now" option to "Build with Parameters" in the project's menu. By default it will run the last build or if a commit hash value from the "Build History" list or GitHub pull request is passed, it will run a specific build. 

In the "Source Code Management" tab tick "Git" option, type the Github repository URL in the "Repository URL" field, in the "Credentials" select box, select the option that was previously created under **1.1.7. Configure Jenkins globally**, click the "Advanced" button then type ```origin``` in "Name" and ```+refs/pull/*:refs/remotes/origin/pr/*``` in "Refspec" field and type ```${sha1}``` in "Branch Specifier" field. Also on "Additional Behaviours" click "Add", choose "Check out to a sub-directory" option and in "Local subdirectory for repo" place ```${sha1}```. This last option is meant fot having the PR code be pulled into separate folders with a folder per PR.

In "Build Triggers" tab tick "GitHub Pull Request Builder" option. You'll see that your previous configs will appear there. Then tick "Use github hooks for build triggering" option. After clicking the "Advanced" button you can add users to the allowed Admins in "Admin list" and place a trigger phrase so that a build can be triggered after a specific comment from one of the admins.

In the "Build Environment" tab you can tick the "Set GitHub commit status with custom context and message (Must configure upstream job using GHPRB trigger)" option and then set the commit status URL, build triggered and started messages to anything you want as well as the build result messages.

#### 1.1.11 Test Jenkins project

The project is now fully configured with all the right settings and the webhooks successfully configured so now if you create a Pull Request on the repository or a new commit to an existent PR Jenkins will automatically build it. Also if the admins comment the trigger phrase on a PR a build will also be triggered for that PR. 

#### 1.1.12 Scripts (Build actions)

Now that everything is setup all that is missing is to add build steps should you want to because all that Jenkins does thus far is pulling the latest code for a PR into the respective folder in the Jenkins workspace when a build is triggered. 

In the "Build" tab press "Add build step", choose "Execute shell" and then you can insert the bash commands you want to run or run a bash script on the Jenkins folder having access to some environment variables available. 

For this project there is no need to run a bash script as a build step because all the scripts that will be run will be related to the multi-container environment and will be run after the Jenkins build. 

For this project the setup process followed until here is enough. Before there were changes to the scope [this bash script](https://github.com/joomla-projects/gsoc17_pr_testing_platform/blob/master/bash_scripts/jenkins_script.sh) would be run as a build step in order to copy the old Joomla! docker compose files into the PR folder, place PHP version and the path to the Joomla! pulled code in the compose file, place the IssueComment code in a folder on Jenkins home folder and post a comment on the PR by running the IssueComment code. To run this script file I placed the script on a ```scripts/``` folder in the standard Jenkins home folder (```/var/lib/jenkins/```) and ran it in the build step:

```
#!/bin/bash

cd ${JENKINS_HOME}/scripts/ && ./joomla_build.sh
```


## Installing composer dependencies
---------------------

### Install/update Dependencies with Composer

```
composer install  or  composer update
```
