FROM php:8.2-apache

# パッケージ更新と必要なパッケージのインストール
RUN apt-get update && apt-get install -y \
      git \
      unzip \
      libonig-dev \
      libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring zip

# APCu のインストールと有効化
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Composer を公式イメージからコピー (Composer はPHPパッケージ管理ツール)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# vhost の DocumentRoot を public に変更
RUN sed -ri \
      -e 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
      /etc/apache2/sites-available/000-default.conf \
    && sed -ri \
      -e 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' \
      /etc/apache2/apache2.conf