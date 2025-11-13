FROM php:7.4-apache

RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apt update && apt install -y libc-client-dev libkrb5-dev libssl-dev

RUN docker-php-ext-configure imap --with-kerberos --with-imap-ssl && docker-php-ext-install imap

COPY ./app/ /var/www/html

COPY ./entrypoint.sh /entrypoint.sh

RUN chmod +x /entrypoint.sh

ENTRYPOINT [ "/entrypoint.sh" ]

RUN chown -R www-data:www-data /var/www/html/

CMD [ "apache2-foreground" ]