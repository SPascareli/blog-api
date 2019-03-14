FROM php:7.2-apache-stretch

# install mongodb php extension
RUN pecl install mongodb

# activate apache mods and php extensions and confs
RUN cp /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/ \
    && echo "short_open_tag = On" > /usr/local/etc/php/conf.d/short-open-tag.ini \
    && echo "extension=mongodb.so" >> /usr/local/etc/php/conf.d/extensions.ini \
    && echo "display_errors=0" >> /usr/local/etc/php/conf.d/errors.ini

# copy the provisioning scripts
COPY provisioning/import.php /provisioning/import.php
COPY vendor/ /provisioning/vendor/

# get the data to be populated in the mongo db
RUN curl https://jsonplaceholder.typicode.com/posts -o /provisioning/posts.json \
 && curl https://jsonplaceholder.typicode.com/comments -o /provisioning/comments.json \
 && curl https://jsonplaceholder.typicode.com/users -o /provisioning/users.json

# copy the modified docker entrypoint that calls the provisioning script
COPY docker-php-entrypoint /usr/local/bin/

# set the new docker entrypoint as executable
RUN chmod +x /usr/local/bin/docker-php-entrypoint

# copy the application files to the webroot
COPY ./app /var/www/html/
COPY ./vendor /var/www/html/vendor