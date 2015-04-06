##PRE_INSTALL NOTES FROM ANTON - c_ratesdemo ##

This is a CakePHP3 Project for uAfrica for real time shipping rates API. 
I have installed localy for Dev the following: 

* PHP5.5
* MySQL
* Apache2
* Composer

Installed CakePHP3 via composer : 

```curl -s https://getcomposer.org/installer | php ```

```composer create-project --prefer-dist cake/php c_ratesdemo```

BUT - All composer json config is already in repo- just required to do: 

```composer update``` 


### Install - Create the Database: 

```CREATE DATABASE `c_ratesdemo_db` ```

The user is defined in the /config/app.php (user = demouser) - You need to copy the app_default.php to app.php and setup
the user for database access - app.php is not copied in git as it contains sensitive access data.


To Create the tables with phinx migration run (in the project root) - Make sure your Db user is setup (you can access mysql with the user,
and remember to add the mysql details in your /config/app.php file)

```bin/cake migrations migrate```


### Setup Apache2

In the /config/site_apache folder is a Apache config file use the bash script in that same folder (setup_apache.sh) to copy the file to
/etc/apache2/sites-available/ , then the script will run a2ensite and restart apache




###NOTE - Some things to note to make your life easier

If using rob Morgan Phinx, do not expect the documenation straight on Phinx to work - phinx create migrationname etc, rather look at the 
CakePHP migrations repo (commands change to things like bin/cake create etc) - have a look here if you run into issues with phinx
https://github.com/cakephp/migrations

** Issues with PHP ? If cakePHP have issues with mcrypt, or your php scripts does not work, make sure PHP is installed with:

```
sudo apt-get install php5 libapache2-mod-php5 php5-mcrypt
```

Add php to dir index : 
```
sudo nano /etc/apache2/mods-enabled/dir.conf
```
make sure index.php is there and better add it to start


In /etc/apache2/apache2.conf set your directives like : 

Where AllowOvreride must not be None but set to All


```
<Directory />
        Options FollowSymLinks
        AllowOverride All
        Require all denied
</Directory>

<Directory /usr/share>
        AllowOverride None
        Require all granted
</Directory>

<Directory /var/www/>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
        Order Allow,Deny
        Allow from all
</Directory>
```

Also make sure  to laod modules with a2enmod (Apache2 Ubuntu):
```
a2enmod rewrite
```

and to be sure PHP5
```
a2enmod php5
```

Restart Apache 
``` sudo /etc/init.d/apache2 restart```

CakePHP3 has issue loading debugkit, well if certain php5 mods not installed - to fix do : 
```
sudo apt-get -y install sqlite
sudo apt-get -y install php5-sqlite
``` 

Look at your logs/error logs to see if you have this issue





# CakePHP Application Skeleton

[![Build Status](https://api.travis-ci.org/cakephp/app.png)](https://travis-ci.org/cakephp/app)
[![License](https://poser.pugx.org/cakephp/app/license.svg)](https://packagist.org/packages/cakephp/app)

A skeleton for creating applications with [CakePHP](http://cakephp.org) 3.0.

This is an unstable repository and should be treated as an alpha.

## Installation

1. Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.
2. Run `php composer.phar create-project --prefer-dist cakephp/app [app_name]`.

If Composer is installed globally, run
```bash
composer create-project --prefer-dist cakephp/app [app_name]
```

You should now be able to visit the path to where you installed the app and see
the setup traffic lights.

## Configuration

Read and edit `config/app.php` and setup the 'Datasources' and any other
configuration relevant for your application.
