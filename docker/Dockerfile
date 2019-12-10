FROM drupal:fpm-alpine

COPY --from=composer /usr/bin/composer /usr/bin/composer
ADD https://github.com/drush-ops/drush-launcher/releases/download/0.6.0/drush.phar /usr/local/bin/drush
RUN rm -rf /var/www/html && chmod 755 /usr/local/bin/drush
RUN apk --update --no-cache add git sqlite mariadb-client
WORKDIR /app
