FROM php:8.1-apache

COPY php/php.ini $PHP_INI_DIR/php.ini
COPY php/log.conf $PHP_INI_DIR/conf.d/zz-log.conf
COPY code/ /var/www/html/

EXPOSE 80 443