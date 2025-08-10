FROM php:8.3-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    default-mysql-client \
    default-libmysqlclient-dev \
    libcurl4-openssl-dev \
    libjpeg-dev \
    libfreetype6-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions
RUN docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd && \
    docker-php-ext-configure mysqli --with-mysqli=mysqlnd && \
    docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    pdo \
    pdo_mysql \
    mysqli \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip \
    curl

# Get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user
RUN useradd -m -u 1000 hiroakiokamura && \
    usermod -a -G www-data hiroakiokamura

# Set working directory
WORKDIR /var/www

# Copy custom php.ini
COPY php.ini /usr/local/etc/php/

# Create storage directory structure
RUN mkdir -p /var/www/storage/logs \
    /var/www/storage/framework/sessions \
    /var/www/storage/framework/views \
    /var/www/storage/framework/cache

# Set permissions
RUN chown -R hiroakiokamura:www-data /var/www && \
    chmod -R 775 /var/www && \
    chmod -R 777 /var/www/storage

# Verify PHP extensions
RUN php -m | grep -i pdo
RUN php -m | grep -i mysqli
RUN php -m | grep -i mysqlnd

# Switch to hiroakiokamura user
USER hiroakiokamura
