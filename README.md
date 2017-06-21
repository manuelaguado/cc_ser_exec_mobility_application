## Acerca de MobilityApplication

MobilityApplication es un marco de trabajo escrito en PHP para la administración de unidades para el transporte de personal privado y público:



##Instalar librerias PHP para manejo de imágenes y librería xml

        -apt-get install php7.0-xml
        -apt-get install php7.0-gd
		-apt-get install php7.0-curl
		-apt-get install php7.0-json		

##Configurar el servidor

		Activar rewrite

			a2enmod rewrite

		Direccionar el server a la carpeta public

##Configuración de MySQL

        -nano /etc/mysql/mysql.conf.d/mysqld.cnf
		Instalar la base de datos MobilityApplication.sql que se encuentra en la raiz

poner al final:

        -sql_mode="NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION"

###Configuracion de locales
              locale-gen "es_MX.UTF-8"
              dpkg-reconfigure locales
              /etc/apache2 reload
              
###Configuracion de la zona horaria

		metodo 1
		agregar al final de my.cnf

			default-time-zone='+00:00'

		metodo 2
		reconfigurar el sistema

			dpkg-reconfigure tzdata

		en cualquiera de los dos casos reiniciar mysql

		verificar que mysql tome las variables del sistema:

			SELECT @@global.time_zone;

		Si no es asi setearlas:

			SET GLOBAL time_zone = 'SYSTEM';

instalar composer:

        -curl -sS https://getcomposer.org/installer | php
        -sudo mv composer.phar /usr/local/bin/composer

## Webhooks

Apuntar webhooks a URL_SITE.webhook/presence

## Cron

	Activar los trabajos cronometrados del archivo que esta en la raiz cambiar la url por la propia

	crontab cron

## certificado ssl

	Activar el mod ssl

		a2enmod ssl

	Direccionar los certificados en sites-available/

## modificar las variables del archivo libs/config.php

	Base de datos,
	Configuracion y URL del sitio

	/*API DE GOOGLE MAPS*/
	/*SOCKET_PROVIDER: ABLY*/
	/*SOCKET_PROVIDER: PUBNUB*/
	/*SOCKET_PROVIDER: PUSHER*/



/*Seleccionar SOCKET_PROVIDER*/
define('SOCKET_PROVIDER','AQUI EL SOCKET PROVIDER',false);

PHP-Console

	https://chrome.google.com/webstore/detail/php-console/nfhmhhlpfleoednkpnnnkolmclajemef?hl=es-419



Para mail via PEAR Mail:
        -Instalamos pear
            sudo apt-get install php-pear

        -Instalamos las librerias de pear necesarias
            sudo pear install mail
            sudo pear install Net_SMTP
            sudo pear install mail_mime

##Permisos para carpetas

		- chown -R www-data:www-data public/tmp/
		- chmod -R www-data:www-data public/plugs/cache/
		- chmod -R www-data:www-data uploads/perfiles/
