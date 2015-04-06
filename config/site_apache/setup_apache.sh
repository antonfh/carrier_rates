#!/bin/bash
sudo apt-get update
sudo apt-get install php5 php5-common php5-intl php5-cli php5-mcrypt php5-sqlite
cp /var/www/carrier_rates/config/site_apache/devtest01.uafrica.com.conf /etc/apache2/sites-available/devtest01.uafrica.com.conf
a2ensite devtest01.uafrica.com.conf
service apache2 restart
a2enmod php5
cd /var/www/carrier_rates
