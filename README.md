# This is a development branch

Do NOT use this for any kind of production

# Requirements

PHP 5.x or better
MTA that can redirect into pipes (e.g. Exim)
Apache 2.x or better
Database backend (mysql, postgres, etc)

# Installation (as root)

## Install global composer 

cd /tmp
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer

## Install global dependencies

pecl install mailparse (note: mbstring should be installed on linux systems, if not add mbstring to the install command)
echo "extension=mailparse.so" > /etc/php5/mods-available/1000-mailparse.ini
ln -s /etc/php5/mods-available/1000-mailparse.ini /etc/php5/cli/conf.d/1000-mailparse.ini
ln -s /etc/php5/mods-available/1000-mailparse.ini /etc/php5/apache2/conf.d/1000-mailparse.ini

## Create local user 

adduser abuseio

## checkout git

cd /opt
git clone -b AbuseIO-4.0 git@github.com:AbuseIO/AbuseIO.git abuseio

## fix permissions

cd /opt
chown -R abuseio:abuseio abuseio
chmod -R 664 abuseio/storage
chown -R abuseio:www-data abuseio/storage
chown -R abuseio:postfix abuseio/storage/mailarchive

## Creating MTA delivery

To test your mail route, send a e-mail to notifier@your-MTA-domain.lan and if you want to use the CLI you can directly
send a EML file into the parser using:

cat file.eml | /usr/bin/php -q /opt/abuseio/artisan --env=local email:parse

Please note that using the 'cat' option might give you a difference with email bodies, for example with line 
termination you'd see a \r\n instead of \n or vice versa. Once you have tested your parser by using the cat method
always use the MTA address to validate your work!

### Postfix
 
echo 'notifier: | "| /usr/bin/php -q /opt/abuseio/artisan --env=local email:parse"' >> /etc/aliasses
newaliasses

## Configuring Apache

You should be able to visit the website at URI /admin/ with a document root at /opt/abuseio/public/

Don't forget: It's highly recommended to add a .htaccess and secure with both IP as ACL filters!

# Installation (as abuseio)

## Install dependencies

cd /opt/abuseio
composer install

## Setting up configuration

Create the file /opt/abuseio/.env with the following hints:

APP_ENV=local (change to production if needed)
APP_DEBUG=true (change to false if needed)
APP_KEY=xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
DB_HOST=localhost
DB_DATABASE=abuseio
DB_USERNAME=username
DB_PASSWORD=password
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_DRIVER=sync

## Installing and seeding database

cd /opt/abuseio
php artisan migrate:install
php artisan migrate
php artisan db:seed
