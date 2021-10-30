#!/usr/bin/env bash

clear
VHOSTFILE="vhost.txt"
USER=$USER
REPOSITORY="https://github.com/nottechguy/hielo-uno.git"

initmessage() {
    echo "Installer"
    echo "Checking required programs...\n"
}

checkprograms() {
    if hash php 2>/dev/null; then
        echo "PHP is installed.      OK"
    else
        echo "PHP is not installed.    FAILED"
    fi
    if hash mysql 2>/dev/null; then
        echo "MySQL is installed.    OK"
    else
        echo "MySQL is not installed.  FAILED"
    fi
    if hash apache2 2>/dev/null; then
        echo "Apache is installed.   OK"
    else
        echo "Apache is not installed. FAILED"
    fi
    if hash git 2>/dev/null; then
        echo "git is installed.      OK"
    else
        echo "git is not installed.    FAILED"
    fi
    if hash composer 2>/dev/null; then
        echo "Composer is installed. OK"
    else
        echo "PHP is not installed.    FAILED"
    fi
    echo "\n"
}

copyvhost() {
    echo "Setting up the virtual host"
    sed -i "s/user/$USER/" $VHOSTFILE
    sudo mv $VHOSTFILE /etc/apache2/sites-availables/hielo1.conf
    sudo a2ensite /etc/apache2/sites-availables/hielo1.conf
    sudo /etc/init.d/apache2 restart
    sudo echo "127.0.0.1  www.hielo-uno.com" >> /etc/hosts

    echo "Everything done. \nOpen the web browser and go to http://www.hielo-uno.com"
}

initmessage
checkprograms
copyvhost