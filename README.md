
# ZopDealer

ZopDealer is a web application for automobile primarily for Inventory Management and Deal Management. 

It is based on Codeigniter for the backend framework and Vue 2 for the Front End. 

## System Requirements
- LAMP Stack
- Composer
- NPM

Currently system is based on Codeigniter 4.0.3 version and PHP 7.4. Use NVM to manage versions of Node and use node version 16.20.2.
For Database we use SQL 5.7.

You can use the following links for the installation guidance: 

`https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-22-04`

`https://www.digitalocean.com/community/tutorials/how-to-install-php-7-4-and-set-up-a-local-development-environment-on-ubuntu-20-04`

Once the setup is done you can add the additional PHP 7.4 extensions by using the following command:

`sudo apt install -y php7.4-{bcmath,bz2,intl,gd,mbstring,mysql,zip,common}`

Download Composer from the following link :

`https://getcomposer.org/download/`

Composer is PHP package manager.

`composer config policy.advisories.block false`

`composer install`

#### Setting Up Apache 2
Once the apache is installed you will need to enable the ssl and rewrite mode by using the following commands.

`sudo a2enmod ssl`

`sudo a2enmod rewrite`

Copy the virtual host file in your apache2 sites folder (`/etc/apache2/sites-available`) from the following link : https://docs.google.com/document/d/1B1rKsfaCCMa9l401xryUXJQNbrxdUX39f_uxHzMmiYY/edit?usp=sharing

Generate the Self-Signed SSL Certificate for the Local environment. 

Following links can be helpful:

https://stackoverflow.com/questions/10175812/how-can-i-generate-a-self-signed-ssl-certificate-using-openssl

https://devcenter.heroku.com/articles/ssl-certificate-self

Once done, replace the path for the SSL Certficates in the Virtual Host file and enable the site for Apache 2 using the command : 

`sudo a2ensite zd.com.conf ` assuming zd.com.conf is the name of the file.

#### Binary Requirements

- PDFTK
 
 This is used for generating the Window Sticker and other PDF on the website.

 Installation commands:

 ```
 sudo apt-get install pdftk

cp vendor/h4cc/wkhtmltoimage-amd64/bin/wkhtmltoimage-amd64 /usr/local/bin/

cp vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64 /usr/local/bin/

chmod +x /usr/local/bin/wkhtmltoimage-amd64 

chmod +x /usr/local/bin/wkhtmltopdf-amd64
```

- Imagick
 This is used for processing images on the windowsticker as well as the overlays on the image.

  Installation commands:

```
  apt-get install php7.4-imagick

  apt-get install imagick

```





