FROM php:8.2-apache

RUN docker-php-ext-install mysqli && \
    a2enmod rewrite

COPY . /var/www/html/

RUN echo '<Directory /var/www/html>\n\
    AllowOverride All\n\
    Require all granted\n\
</Directory>' > /etc/apache2/conf-enabled/allow-override.conf

EXPOSE 80
