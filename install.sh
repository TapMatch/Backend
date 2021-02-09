#!/bin/sh
sudo apt update
sudo apt install apache2 -y
sudo apt install mysql-server -y
sudo apt install php libapache2-mod-php php-mysql -y
sudo apt install php php-cli php-fpm php-json php-common php-mysql php-zip php-gd php-mbstring php-curl php-xml php-pear php-bcmath -y
sudo apt install curl php-cli php-mbstring git unzip -y
sudo systemctl restart apache2
sudo curl -s https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo apt install flameshot -y
sudo apt install git g++ build-essential qt5-qmake qt5-default qttools5-dev-tools -y
sudo apt install docker.io -y
sudo systemctl start docker
sudo systemctl enable docker
sudo a2enmod rewrite
wget https://get.symfony.com/cli/installer -O - | bash
