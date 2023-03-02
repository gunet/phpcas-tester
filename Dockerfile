FROM php:8.1-apache

# Enable ssl
COPY apache/default-ssl.conf /etc/apache2/sites-available/
RUN a2enmod socache_shmcb ssl && \
    a2ensite default-ssl

COPY certs/privkey.pem /etc/ssl/private/
COPY certs/server.crt /etc/ssl/certs/

COPY php/php.ini $PHP_INI_DIR/php.ini
COPY php/log.conf $PHP_INI_DIR/conf.d/zz-log.conf
COPY code/ /var/www/html/

EXPOSE 80 443
