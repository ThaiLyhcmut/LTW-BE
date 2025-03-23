# Sử dụng PHP-FPM 8.2
FROM php:8.2-fpm

# Cài đặt các extensions cần thiết (pdo, pdo_mysql)
RUN docker-php-ext-install pdo pdo_mysql

# Cài đặt Composer thủ công
RUN apt-get update && apt-get install -y unzip curl \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy mã nguồn vào container
COPY . /var/www/html

# Cài đặt thư viện PHP từ Composer
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho PHP chạy đúng
RUN chown -R www-data:www-data /var/www/html

# Expose cổng 8000 cho PHP-FPM
EXPOSE 9000
