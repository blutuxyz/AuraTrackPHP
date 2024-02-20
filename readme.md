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
If thats not the case you can try running `systemctl start apache2` or `systemctl start nginx`. 


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


### Webserver Setup and AuraTrack download
Let's go ahead, and configure our webserver.

We're going to create the directory, where we're going to download AuraTrack:
```
mkdir -p /var/www/auratrack
```
and then we're going to jump into that folder:
```
cd /var/www/auratrack
```
From here we can download the AuraTrack Setup:
```
git clone https://github.com/AuraTrackSetup.git . #The "." at the end means, that the repository is directly downloaded to that folder, without creating another folder called "AuraTrackSetup".
```
Now lets setup the permissions:
```
chmod 777 index.php
chown -R www-data:www-data index.php
```
This allows us that the webserver, or that file, can make changes to our server (for example installing packages, connecting to the database etc.). 

#### Now we're going to setup the configuration file for our webserver:
First of all, we're going to use the editor `nano` to make the webserver config file. If `nano` isn't installed, run this command: `apt install nano -y`:

Before continuing, make sure you disable the default apache config file:
`a2dissite 000-default.conf`
Now lets continue:
```
nano /etc/apache2/sites-available/auratrack.conf
```
Paste following content in there:
```
<VirtualHost *:80>
  ServerName <domain>
  DocumentRoot "/var/www/auratrack/"
  
  AllowEncodedSlashes On
  
  php_value upload_max_filesize 100M
  php_value post_max_size 100M
  
  <Directory "/var/www/pterodactyl/">
    AllowOverride all
    Require all granted
  </Directory>
</VirtualHost>
```
**Please note that this configuration is specifically only for HTTP which isn't recommended. To setup HTTPS you need a SSL certificate and some additional config, which you can create with ACME.SH.**

Now let's restart our Apache Webserver (it's recommended to fully reboot your server by using `reboot`)
```
systemctl restart apache2
```

Thats it! Now we can continue through the guided setup by AuraTrack.
To do that, open your favorite web browser and type in `http://yourserver.ip/index.php`. Now you can select what you want to install. In our case, `AuraTrack PHP Edition`. This will check all dependencies and if something is missing, it will help you how to install it or fix it.


Wohoo 🎉! Now we have successfully installed the AuraTrack PHP Edition!



## Development
Because AuraTrack is open-source, you can change everything you want in the code. AuraTrack is made with HTML, PHP, JavaScript and TailwindCSS. If you only want to edit the PHP, HTML and JS you can simply start, but if you start adding CSS Classes associated with TailwindCSS you need to run these commands.
Let's start:

First, we have to install the dependencies, again!
The dependencies are:
- Node.JS
- NPM (Should be automatically installed with Node.JS)

To do that, we're going to run following commands:
The easiest way to install Node.JS is by using NVM. There is a one-liner, for installation:
```
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.7/install.sh | bash
```
After that we have to load up NVM. To do this, run following:
```
export NVM_DIR="$([ -z "${XDG_CONFIG_HOME-}" ] && printf %s "${HOME}/.nvm" || printf %s "${XDG_CONFIG_HOME}/nvm")"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh" # This loads nvm
```
Now we can simply install Node.JS:
This should work with any Node.JS version, but we're using Node.JS v21:
```
nvm install 21
```
Thats it, no we have installed Node.JS!

To continue, we have to change the directory to the AuraTrack Installation, by default, it is `/var/www/auratrack/`:
```
cd /var/www/auratrack
```

Let's go ahead and start installing the TailwindCSS CLI:
```
npm install -D tailwindcss
```
or just run `npm install`, because normally there should be the default package.json file.

Now lets use the TW-CSS CLI to watch and build the files:
```
npx tailwindcss -i ./input.css -o ./output.css --watch
``` 
Now you can start editing your code!

Please note, that this is just experimental, and you should head over to the official TailwindCSS Documentation: https://tailwindcss.com/docs/installation

