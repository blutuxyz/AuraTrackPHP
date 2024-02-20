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