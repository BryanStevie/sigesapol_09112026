FROM php:5.6-apache

# Debian stretch está archivado: apuntar apt al archivo histórico
RUN sed -i -e 's|deb.debian.org|archive.debian.org|g' \
           -e 's|security.debian.org|archive.debian.org|g' \
           -e '/stretch-updates/d' /etc/apt/sources.list \
 && echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99archive

# Extensiones: Postgres + lo que piden dompdf, maatwebsite/excel y simple-qrcode
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/local/bin/
RUN install-php-extensions pdo_pgsql pgsql mbstring zip gd mcrypt

# Composer 2.2 LTS (último que soporta PHP 5.x) + 7z para gemorroj/archive7z
COPY --from=composer:2.2 /usr/bin/composer /usr/bin/composer
ENV COMPOSER_MEMORY_LIMIT=-1
RUN apt-get update && apt-get install -y --no-install-recommends git unzip p7zip-full \
 && rm -rf /var/lib/apt/lists/*

# Ajustes de PHP
RUN printf 'date.timezone=America/Lima\nmemory_limit=512M\nupload_max_filesize=50M\npost_max_size=50M\n' \
    > /usr/local/etc/php/conf.d/custom.ini

# Apache sirviendo /public con .htaccess
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
 && printf '<Directory ${APACHE_DOCUMENT_ROOT}>\n    AllowOverride All\n</Directory>\n' > /etc/apache2/conf-available/override.conf \
 && a2enconf override && a2enmod rewrite

WORKDIR /var/www/html