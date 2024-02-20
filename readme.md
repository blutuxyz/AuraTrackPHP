# Welcome to AuraTrack

## ⚠️Disclaimer: This version of AuraTrack is written in PHP and currently in the Beta-Release-Phase and should not be used in commercial use. There are still bugs and many security leaks!⚠️

### What is AuraTrack:
AuraTrack is a completely open-source Tracking Software for your online store. This version is written in PHP, but there is also a version written in JS, which is currently not available, because it's still WIP.


## Basic Installation of AuraTrack:
### Requirements
Please note that this installation guide is for a fresh-installed Ubuntu 20.04 environment!

- A freshly set up Ubuntu 20.04 (Don't install anything!)
- Access to the server's console, e.g. with SSH

### Dependencies
To get AuraTrack working, you need to install a few dependencies first. Open your terminal (or connect via SSH) and enter the following commands one by one.
Here is a list of what we're going to install:
- A webserver (in our case Apache)
- PHP 8.0
- A MySQL Server (MariaDB)
- Git, to clone the AuraTrack PHP repository (not needed if you downloaded it manually or if you move it onto your server via SFTP)

Now we're going to install everything.

First we're going to update your software packages.

```
apt update 
apt upgrade -y #The -y means that you will not be asked if you want to continue installing every single package
```
This could take about 5-10 minutes depending on your system.

Now we need to install the Web Server.
You can use Apache or nginx. If you want to install nginx, some of the configuration will be different but overall it should work fine. For now let's stick to Apache.

```
apt install apache2 -y
```
or for nginx:
```
apt install nginx -y
```

Now you can check if your webserver is reachable, by going to your browser and typing http://yourserver.ip/ .
If thats not the case you can try running `systemctl apache2 start` or `systemctl nginx start`. 


Obviously we need to install PHP for the AuraTrack PHP Edition to work. AuraTrack is tested with PHP 8.0, but other PHP versions may also work. Lets continue installing PHP 8.0:

```
apt install software-properties-common curl apt-transport-https ca-certificates gnupg -y
LC_ALL=C.UTF-8 add-apt-repository -y ppa:ondrej/php
apt update
apt install php-8.0 php-8.0-{common,cli,gd,mysql,mbstring,bcmath,xml,fpm,curl,zip} -y
```

This could take a few more minutes.
Now lets go ahead and install MariaDB. To do that, run following commands:
```
curl -sS https://downloads.mariadb.com/MariaDB/mariadb_repo_setup | sudo bash
apt update
apt install mariadb-server -y
```

Now we can login to our MySQL/MariaDB server and create the database and the user account.
```
mysql -u root -p #Press enter


CREATE USER 'AuraTrack'@'127.0.0.1' IDENTIFIED BY 'EnterAPassword'; #Change user name and password as you want
CREATE DATABASE AuraTrackDB; #Here you can change the database name.
GRANT ALL PRIVILEGES ON AuraTrackDB.* TO 'AuraTrack'@'127.0.0.1' WITH GRANT OPTION; #Also make sure that you change the values as before, otherwise this command will fail.
#Now you can exit from mysql with the
exit
#command
```

The final dependency is git. To do that, run following command:
```
apt install git -y
```
Now all dependencies are installed. Lets continue with the AuraTrack Setup.
