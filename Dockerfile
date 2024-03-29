FROM php:8.1-apache

# Enable ssl
COPY apache/default-ssl.conf /etc/apache2/sites-available/
RUN a2enmod socache_shmcb ssl alias && \
    a2ensite default-ssl

COPY certs/privkey.pem /etc/ssl/private/
COPY certs/server.crt /etc/ssl/certs/

COPY php/php.ini $PHP_INI_DIR/php.ini
COPY php/log.conf $PHP_INI_DIR/conf.d/zz-log.conf
COPY code/ /var/www/html/

HEALTHCHECK --interval=5s --timeout=2s --start-period=5s --retries=5 CMD /usr/bin/pgrep -c -u www-data apache2

# Allow for a redirect folder
# The ${REDIR_FOLDER} will be redirected to DocumentRoot
ENV REDIR_FOLDER=tester
ENV TZ=Europe/Athens
ENV CAS_SERVER=localhost
ENV CAS_CONTEXT=/cas
ENV CAS_PORT=8443
ENV CAS_VERSION=3.0
ENV CAS_SERVICE_NAME=https://localhost

EXPOSE 80 443
