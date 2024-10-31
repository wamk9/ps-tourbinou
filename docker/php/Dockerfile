FROM php:8.3-apache
COPY apache-config.conf /etc/apache2/sites-available/000-default.conf
RUN chown -R www-data:www-data /var/www
RUN a2enmod rewrite
RUN service apache2 restart
RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY php_init_db.sh /docker-entrypoints/php_init_db.sh
ENTRYPOINT ["/docker-entrypoints/php_init_db.sh"]
CMD ["apache2-foreground"]