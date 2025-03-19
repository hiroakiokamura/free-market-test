FROM php:8.3.12-fpm

# 必要なパッケージをインストールし、PHP拡張モジュールを有効化
RUN apt update \
    && apt install -y --no-install-recommends \
    default-mysql-client \
    zlib1g-dev \
    libzip-dev \
    unzip \
    libcurl4-openssl-dev \
    libonig-dev \
    && docker-php-ext-install pdo_mysql zip curl mbstring \
    && apt clean \
    && rm -rf /var/lib/apt/lists/*

# Composerのインストールを追加
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

# 作業ディレクトリの設定
WORKDIR /var/www/html

# カスタムphp.iniの配置
COPY php.ini /usr/local/etc/php/
