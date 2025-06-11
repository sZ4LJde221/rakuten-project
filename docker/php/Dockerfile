FROM php:8.2-apache

# 必要な拡張のインストール
RUN apt-get update && apt-get install -y \
      git \
      unzip \
      libonig-dev \
      libzip-dev \
      libpq-dev \
    && docker-php-ext-install pdo_mysql pdo_pgsql mbstring zip

# APCu インストール
RUN pecl install apcu \
    && docker-php-ext-enable apcu

# Composer を使えるようにする
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# アプリケーションコードをコピー
WORKDIR /var/www/html
COPY . .

# Composer 依存をインストール
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Apache の DocumentRoot を public に切り替え
RUN sed -ri \
      -e 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' \
      /etc/apache2/sites-available/000-default.conf \
    && sed -ri \
      -e 's|<Directory /var/www/html>|<Directory /var/www/html/public>|g' \
      /etc/apache2/apache2.conf

# ポートとエントリポイントはデフォルトのまま
