FROM php:8.3-apache
RUN docker-php-ext-install pdo_mysql \
    && printf '<Directory /var/www/html>\n    AllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/muscari.conf \
    && a2enconf muscari
COPY . /var/www/html/
