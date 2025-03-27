# Sử dụng PHP 8.2 CLI làm base image
FROM php:8.2-cli

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Cài đặt Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép toàn bộ mã nguồn vào container
COPY . .

# Chạy Composer install để cài đặt dependencies
RUN composer install --no-dev --optimize-autoloader

# Mở cổng 8000
EXPOSE 8000

# Lệnh khởi động container
CMD ["php", "-S", "0.0.0.0:8000", "-t", "/var/www/html"]
