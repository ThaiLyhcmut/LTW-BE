FROM php:8.2-cli

RUN apt-get update && apt-get install -y git unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Copy composer binary
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# Copy vendor và toàn bộ source
COPY . .

# EXPOSE và CMD như cũ
EXPOSE 8000
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
