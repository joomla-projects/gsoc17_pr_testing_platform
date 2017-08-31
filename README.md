#  GSoC 2017 Joomla PR Testing Platform
![JGSoC](http://sempreupdate.com.br/wp-content/uploads/2016/06/gsoc-joomla-2016.jpg)

## Introduction
---------------------
* You can check the working prototype for this project here: **[PR Testing Platform](https://dbox-tests.ml/)**

* And the documentation here: **[PR Testing Platform JDoc page](https://docs.joomla.org/PR_Testing_Platform)**

The **main goal** here is to automate the creation/setup of a testing environment for Joomla! CMS and with this shift the tester’s focus entirely to what really matters and test Joomla! instances almost instantaneously.

In the end the tester won’t need to configure his/her environment manually and would no longer need a more technical knowledge regarding the setup and deployment processes, would also benefit more technically advanced users as well because it also saves them from having a local Joomla! Installation and saves them time as well.

We have a **[tests requests website](https://dbox-tests.ml/)** based on the **[issue tracker](https://issues.joomla.org/)** with GitHub authentication where the user can request a test for a PR in particular, after choosing the PR to test the user can choose the PHP version and then waits for the Joomla! instance to be prepared having a view for the instance with all the info regarding that instance plus a no-patch instance for the target branch which the user can use for comparison with the PR instance.

Before going into the setup process in more detail let's go over each part of the platform.

The platform is composed of 3 parts, the **[Jenkins](https://jenkins.dbox-tests.ml/) setup**, the **multi-container setup** based on **[Devilbox](https://github.com/cytopia/devilbox)** and the **[website for the tests requests](https://dbox-tests.ml/)**.

## Jenkins setup

![Jenkins](https://raw.githubusercontent.com/docker-library/docs/3ab4dafb41dd0e959ff9322b3c50af2519af6d85/jenkins/logo.png)

Jenkins's role here is to be our connection to the Github API and the repository, it's intended to build any incoming Pull Requests / Commits. Jenkins is a cross platform CI tool for automation with a large base of plugins which support building, deployment and automation of any project.

For this project the [Github Pull Request builder plugin](https://wiki.jenkins.io/display/JENKINS/GitHub+pull+request+builder+plugin) is used in order to build pull requests.

We are running builds in Jenkins server based on a set of events like creating a PR, pushing commits, commenting PRs, etc. A Jenkins project will be created to run these builds for Github pull request events like create, push, comment, close and reopen. 

Besides the [main job](https://jenkins.dbox-tests.ml/job/Test-repo-1/) to build the PRs there will also be [another Jenkins job](https://jenkins.dbox-tests.ml/job/Test-repo-1-branches/) to build the PR's target branch for comparison later on the tests requests website.

## Multi-container Setup

For the multi-container setup Docker Compose was used and the platform stack was based on Devilbox which is a highly customisable LAMP/LEMP and MEAN stack replacement based purely on docker and docker-compose that supports an unlimited number of projects for which vhosts and DNS records are created automatically. You can learn more about Devilbox on its [github repository](https://github.com/cytopia/devilbox) documentation.

I heavily modified the Devilbox stack in order to allow having 3 different PHP version services each with its webserver behind a proxy which distributes the requests according to the php version embedded in the instances URL. The next diagram exemplifies how the services stack is set up according to the docker-compose:

![multi-container-stack](https://user-images.githubusercontent.com/6710380/29897396-9ccd8cfa-8dd8-11e7-8f91-d2fa5a7e7caa.png)

So in the docker compose for this project there are 14 containers running, each one with a function:

* **Bind DNS server container**: The devilbox uses its own DNS server internally (your host computer can also use it for Auto-DNS) in order to resolve custom project domains defined by TLD_SUFFIX in the .env file. To also be able to reach the internet from inside the container there must be some kind of upstream DNS server to ask for queries. 

* **PHP 5.6, 7.0 and 7.1 containers**: The php containers are the center of all the containers, everything happens in there and all the instances will be located inside these 3 containers. All the php modules and tools will be made available inside. The remote ports and remote sockets are made available to the php container by using **[socat](https://linux.die.net/man/1/socat)**. It uses **[socat](https://linux.die.net/man/1/socat)** to forward the remote **mysql**, **pgsql**, **redis** and **memcached** ports (3306, 5432, 6379 and 11211 respectively) on each of these 4 containers to its own at 127.0.0.1 (127.0.0.1:3306, 127.0.0.1:5432, 127.0.0.1:6379, 127.0.0.1:11211).

* **Webserver container for each PHP version**: These are the Apache/Nginx webserver (currently only working with Nginx, changed only config files from Nginx for this project) containers for each of the PHP version containers, each one is linked to the respective php-fpm container through each php-fpm socket, each on the same port (9000). 

* **Jenkins container**: Jenkins was added to the service stack for this docker compose. This is the container where everything Jenkins related will be located, the Jenkins jobs and the workspace where the code will be pulled into for each job.

* **[JWilder Nginx Proxy](https://github.com/jwilder/nginx-proxy)**: This is the container for the automated nginx proxy for Docker containers using docker-gen which sets up a container running nginx and docker-gen. docker-gen generates reverse proxy configs for nginx and reloads nginx when containers are started and stopped. This reverse proxy was needed in order to forward the requests correctly according to the php version of the URL that's being accessed. There was a problem with knowing which of the webservers to send the requests because there was no way to know to what version an URL corresponded. I solved that issue by placing the php version on the url and using a regular expression in the VIRTUAL_HOST to proxy the requests for each php version to the right webserver container <tt>(~^56.\*\\.dbox-tests\\.ml$$, ~^70.\*\\.dbox-tests\\.ml$$ and ~^71.\*\\.dbox-tests\\.ml$$)</tt>.

* **Letsencrypt Nginx Proxy Companion**: Container for SSL Support using [letsencrypt](https://letsencrypt.org/). The [letsencrypt-nginx-proxy-companion](https://github.com/JrCs/docker-letsencrypt-nginx-proxy-companion) is a lightweight companion container for the nginx-proxy. It allows the creation/renewal of Let's Encrypt certificates automatically side by side with JWilder's nginx proxy. Currently it only generates a certificate for the main tests requests website and for Jenkins, the Joomla! instances URLs have no SSl certificates yet because the way the proxy e configured with the regular expressions the only way to have SSL certificates for those instances without constantly having to bring down the docker compose would be to issue wildcard SSL certificates and [letsencrypt will only allow this in 2018](https://letsencrypt.org/2017/07/06/wildcard-certificates-coming-jan-2018.html).

* **Memcached, PostgreSQL, MySQL and Redis containers**: These are the containers for the PostgreSQL and MySQL databases, Memcached memory object caching system and Redis which is an open source, in-memory data structure store, used as a database, cache and message broker.

Besides all of these services in the docker compose, there is also a bridge docker network in order to allow the containers that are connected to it to communicate with each other via IP address. Each container has an assigned internal IP address and is connected to that network in the docker-compose file.

```
###############################
# NETWORK
###############################
networks:
  app_net:
    driver: bridge
    driver_opts:
      com.docker.network.enable_ipv6: "false"
    ipam:
      driver: default
      config:
        - subnet: 172.16.238.0/24
          gateway: 172.16.238.1
```

To bring up the docker compose just execute the <tt>docker-compose up -d</tt> command, to bring down the docker compose execute the <tt>docker-compose down</tt> command and to look at the logs from the containers execute <tt>docker-compose logs -f</tt> or <tt>docker-compose logs</tt> command. Also to list the containers running from the docker compose run <tt>docker-compose ps</tt>. Should you want to update the images from each container execute the <tt>update-docker.sh</tt> script in the docker-compose base folder.

## Tests Requests Website

The part from the last phase is the [Tests Requests Website](https://dbox-tests.ml/) which was based on the [Issue Tracker](https://issues.joomla.org/), basically added an MVC app to the tracker for the Joomla! instances, changed a bit the script to fetch the issues from the issue tracker to only fetch PRs and added a field to the Issues table to know which PRs are mergeable or not fetching only the mergeable ones (the ones with no conflicts). 

In each instance view there is some info about the instance like PHP version, target branch, link to the PR and login credentials. You also have the buttons to go to the instance and another to an instance with the target branch for that PR in order to compare the Patched Joomla! instance with the No Patch instance. 

The instances are added to and removed from its respective PHP version container by using docker exec to enter those containers from inside the container where the website is located. This is achievable by sharing the docker socket descriptor file and the docker executable as read only to that container in order to be able to use docker commands within a container. JWilder nginx proxy does something similar in order to automatically generate the configuration file based on the existent containers in the same network.

The website is located inside one of the PHP version containers (PHP 7.1).

## Initial Setup

After downloading the code from this repository you have to setup [Docker](https://www.docker.com/) and [Docker Compose](https://github.com/docker/compose) on your machine or server and setup the permissions on the folders and files. It's important from this point forward that all steps are thoroughly followed. It's also important to create a Github Repository where you can make PRs with Joomla code and a Github user for tests.

### Docker & Docker Compose

#### Docker CE

First thing to install is Docker, consider for these instructions an Ubuntu server. The documentation explains very well the steps to install Docker CE, you can check [here](https://docs.docker.com/engine/installation/linux/docker-ce/ubuntu/) for Ubuntu and for other OS's check [here](https://docs.docker.com/engine/installation/#server). 

For installing on Ubuntu follow the next steps which are the same you can find in the docker documentation:

1. Update the apt package index:
```
$ sudo apt update
```

2. Install packages to allow apt to use a repository over HTTPS:
```
$ sudo apt install \
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common
```

3. Add Docker’s official GPG key:
```
$ curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
```

4. Verify that the key fingerprint is ```9DC8 5822 9FC7 DD38 854A E2D8 8D81 803C 0EBF CD88```.
```
$ sudo apt-key fingerprint 0EBFCD88
pub   4096R/0EBFCD88 2017-02-22
      Key fingerprint = 9DC8 5822 9FC7 DD38 854A  E2D8 8D81 803C 0EBF CD88
uid                  Docker Release (CE deb) <docker@docker.com>
sub   4096R/F273FCD8 2017-02-22
```

5. Use the following command to set up the stable repository. You always need the stable repository, even if you want to install builds from the edge or test repositories as well. To add the edge or test repository, add the word edge or test (or both) after the word stable in the commands below. <tt>Note: The lsb_release -cs sub-command below returns the name of your Ubuntu distribution, such as xenial. Sometimes, in a distribution like Linux Mint, you might have to change $(lsb_release -cs) to your parent Ubuntu distribution. For example, if you are using Linux Mint Rafaela, you could use trusty.</tt>
    
    - amd64:
    ```
    $ sudo add-apt-repository \
       "deb [arch=amd64] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
    ```

    - armhf:
    ```
    $ sudo add-apt-repository \
       "deb [arch=armhf] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
    ```

    - s390x:
    ```
    $ sudo add-apt-repository \
       "deb [arch=s390x] https://download.docker.com/linux/ubuntu \
       $(lsb_release -cs) \
       stable"
    ```

Now to install Docker CE:

6. Update the apt package index:
```
$ sudo apt update
```
7. Install the latest version of Docker CE.
```
$ sudo apt install docker-ce
```

#### Docker Compose

Now to install Docker Compose you can check the tutorial [here](https://www.digitalocean.com/community/tutorials/how-to-install-docker-compose-on-ubuntu-16-04) or follow the next few steps that are explained in the tutorial: 

1. First check the latest stable release [here](https://github.com/docker/compose/releases).

2. Copy the release name (1.15.0 for example) and execute the next command by placing the right release:
```
$ sudo curl -o /usr/local/bin/docker-compose -L "https://github.com/docker/compose/releases/download/1.15.0/docker-compose-$(uname -s)-$(uname -m)"
```

3. Next set the permissions:
```
$ sudo chmod +x /usr/local/bin/docker-compose
```

4. Then verify if the installation was successful by checking the version:
```
$ docker-compose -v
```
This should print out the version that was installed like this:
```
docker-compose version 1.15.0, build e12f3b9
```

### Folders and Files Permissions

Now that Docker is installed the next step, and a very important one, is to correctly set up the permissions on the folders, sub-folders and files on the docker-compose.yml base folder. 

1. cd over to where the docker-compose.yml file is located.

2. Give 755 permissions to every folder and subfolder in the folder you are in.
```
$ find ./ -type d -exec chmod 755 {} \;
```

3. Give 644 permissions to every file in every folder and subfolder in the folder you are in.
```
$ find ./ -type f -exec chmod 644 {} \;
```

4. Add your user to www-data group and make www-data user and group owner of the project folder:
```
$ usermod -a -G www-data youruser && chown -R www-data:www-data ./
```

5. Add your user to the docker group and add the user as owner of the docker socket file so that the user inside the container can execute docker commands:
```
$ usermod -aG docker youruser && chown 1000:docker /var/run/docker.sock
```

6. Still in the docker-compose.yml base folder give 777 permissions to all bash scripts there and to all files inside the <tt>files/</tt> folder.
```
$ chmod 777 *.sh && chmod -R 777 files/
```
