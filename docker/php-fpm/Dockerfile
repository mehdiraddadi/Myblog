#FROM php:fpm-alpine
#
#COPY wait-for-it.sh /usr/bin/wait-for-it
#
#RUN chmod +x /usr/bin/wait-for-it
#
#RUN apk --update --no-cache add git
#
#RUN docker-php-ext-install pdo_mysql
#
#RUN apk upgrade --update && apk add \
#  coreutils \
#  freetype-dev \
#  libjpeg-turbo-dev \
#  libltdl \
#  libmcrypt-dev \
#  libpng-dev \
#&& docker-php-ext-install -j$(nproc) gd
#
#COPY --from=composer /usr/bin/composer /usr/bin/composer
#
#WORKDIR /var/www
#
#CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm
#
#EXPOSE 9000

# docker/php-fpm/Dockerfile

FROM php:fpm-alpine
COPY wait-for-it.sh /usr/bin/wait-for-it
RUN chmod +x /usr/bin/wait-for-it
RUN apk --update --no-cache add git
RUN docker-php-ext-install pdo_mysql
COPY --from=composer /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
CMD composer install ; wait-for-it database:3306 -- bin/console doctrine:migrations:migrate ;  php-fpm
EXPOSE 9000
