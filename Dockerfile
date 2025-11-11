FROM php:8.2-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY ./app/ /var/www/html

COPY ./entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT [ "/entrypoint.sh" ]

RUN chown -R www-data:www-data /var/www/html/

CMD [ "apache2-foreground" ]