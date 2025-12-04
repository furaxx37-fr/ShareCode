FROM php:8.1-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql mysqli

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Copy application files
COPY . /var/www/html/sharecode/

# Set working directory
WORKDIR /var/www/html/sharecode

# Set proper permissions
RUN chown -R www-data:www-data /var/www/html/sharecode \
    && chmod -R 755 /var/www/html/sharecode

# Configure Apache DocumentRoot
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/sharecode\n\
    <Directory /var/www/html/sharecode>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
