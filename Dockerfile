# Sử dụng một image cơ bản
FROM php:8.2-apache

# Cài đặt các extension PHP và các công cụ khác cần thiết
RUN docker-php-ext-install pdo_mysql
RUN a2enmod rewrite

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Sao chép mã nguồn dự án vào container
COPY . /var/www/html

# Cài đặt các gói composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-interaction

# Thiết lập quyền truy cập cho storage và bootstrap
RUN chown -R www-data:www-data storage bootstrap/cache
RUN chmod -R 775 storage bootstrap/cache

RUN a2ensite 000-default.conf
RUN a2enmod rewrite

# Mở cổng Apache
EXPOSE 80

# Lệnh chạy khi container được khởi động
CMD ["apache2-foreground"]
