# Set base image (host OS)
FROM php:7.4-apache

# Set maintainer information
LABEL org.opencontainers.image.source=https://github.com/ByPikod/php-blog
LABEL org.opencontainers.image.authors="admin@yahyabatulu.com"

# Set working directory
WORKDIR /var/www/html
# Copy project files to the docker image
COPY src .
# Copy apache config file to the docker image
COPY ./site.conf /etc/apache2/sites-available/my-site.conf

# Configurations
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf
RUN a2enmod rewrite
RUN a2dissite 000-default
RUN a2ensite my-site
RUN service apache2 restart

# Install composer
RUN cd /tmp && curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

# Install dependencies 
RUN composer install

# Dump autoload
RUN composer dump-autoload

# Expose HTTP port
EXPOSE 80
# Start Apache
CMD ["apache2-foreground"]