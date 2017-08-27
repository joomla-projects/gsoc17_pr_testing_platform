## Virtual environment server

The purpose is to create an "instant" virtual operating system including an AMP stack.

So you basically have to issue only one line on the CLI to get a working instance of the JTracker project for local development.

### Requirements

* [VirtualBox](https://www.virtualbox.org/)
* [Vagrant](http://www.vagrantup.com/)
* The ability to use your operating systems command line interface (Need a [Tutorial](http://lifehacker.com/5633909/who-needs-a-mouse-learn-to-use-the-command-line-for-almost-anything) ?).

**Note** If you are a happy [debian](http://debian.org) (sid) user, you can get everything you need using:

```
# apt-get install virtualbox vagrant
```

### Start Up

* Clone or download this repository.
* `cd to/the/path` where you downloaded/checked out the code
* `vagrant up` - **NOTE**: The very first startup will probably take some minutes to complete, since packages have to be downloaded. Time depends, as always, on your ISP.<br />Subsequent starts will take about 10 secs.
* **Test**: Open http://127.0.0.1:2345 in your browser. (The site should show up with a database error => proceed with setup)

### Setup

You have to run the setup from the command line of your virtual "guest" operating system.

* `cd to/the/path` where you downloaded/checked out the code
* `vagrant ssh` - Welcome to Linux ;)
* `cd /vagrant` - ! **Note** that this is actually the repository root **outside** of your virtual machine which is mounted as a [shared folder](https://www.virtualbox.org/manual/ch04.html#sharedfolders) !! (!)
* Follow the general setup instructions.<br />`bin/jtracker install`
* The config file `config.vagrant.json` will be used for setup.

**NOTE** The `config.vagrant.json` file is under version control so you might want to issue the following command to ignore changes made to this file:
`git update-index --assume-unchanged etc/config.vagrant.json`

**NOTE** In order to work together with GitHub when developing, please sign up for a [Developer application](https://github.com/settings/applications) in GitHub. And you will need to fill in the Authorization callback URL as http://localhost:2345. 

**NOTE** Sometimes you may come up with the permission error with the files in logs dir and the files in `JROOT/htdocs/images/avatars`(after you setup GitHub and try to log in with GitHub). Just simply `cd to/the/path` in terminal where you downloaded/checked out the code, then run `chmod 0777 -R logs` and `chmod 0777 -R /htdocs/images/avatars` to give full permission for the application to read/write the logs and avatars folder.

## That should be it.

Go for the code :wink:

### Shut down and Destroy

When you are finished and want to stop the VM to work with it later, you should either run `halt` or `suspend`, the latter requiring a bit more disc space while providing a somewhat faster startup.

* `vagrant halt` OR `vagrant suspend`

To delete the whole VM run

* `vagrant destroy`

### Additional Features

The TrackerApplication has been modified to look for an environment variable `JTRACKER_ENVIRONMENT`.
If it is set to "something", a config file with the same name will be loaded.

**Example**
You may set the environment variable from inside a `VirtualHost` directive in one of your Apache config files.

```
<VirtualHost *:80>
	...
    SetEnv JTRACKER_ENVIRONMENT foobar
	...
</VirtualHost>
```

With the environment variable set to `foobar` you will have to create the file `config.foobar.json`.

**NOTE** that you'll have to supply the environment variable separately to the CLI application - depending on your OS:

----

**Note:** Apache and PHP are configured to write log files to the `logs` directory at the repo root **outside** the virtual machine. They are at "debug" level, so they are growing fast. Consider [logrotate](http://linux.die.net/man/8/logrotate) or similar.

----
**P.S.:** You might also like: [elkuku/vagrant-joomla-cms](https://github.com/elkuku/vagrant-joomla-cms) :wink:
