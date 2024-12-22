# syntax=docker/dockerfile:1.7-labs
FROM php:8.2-apache-bookworm
COPY confs/apache.conf /etc/apache2/sites-enabled/000-default.conf
COPY --exclude=confs/ . /var/www/html
RUN apt update
RUN apt install -y git \
                libldap-common \
                openssl \
                tar \
                wget \
                zlib1g
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
