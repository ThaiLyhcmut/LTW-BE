# Sử dụng PHP 8.2 CLI làm base image
FROM php:8.2-cli

# Cài đặt các extension PHP cần thiết và Git
RUN apt-get update && apt-get install -y git unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql

# Cài đặt Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép file composer trước để tận dụng cache layer
COPY composer.json composer.lock ./

# Cài đặt các dependencies
RUN composer install --no-dev --optimize-autoloader

# Sau đó mới copy toàn bộ source (tránh mất cache khi chỉ sửa code)
COPY . .

# Mở cổng 8000
EXPOSE 8000

# Lệnh khởi động container
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
