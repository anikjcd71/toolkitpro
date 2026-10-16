FROM php:8.3-apache
RUN docker-php-ext-install pdo_mysql mysqli && a2enmod rewrite headers
RUN sed -ri 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
