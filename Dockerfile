# Sử dụng base image có sẵn PHP và Nginx
FROM php:8.1-fpm-alpine

# Cài đặt các extension PHP cần thiết cho Laravel
RUN apk add --no-cache \
    nginx \
    supervisor \
    bash \
    curl \
    mariadb-client \
    postgresql-dev \
    git \
    nodejs \
    npm

# Cài đặt các extension PHP cần thiết cho Laravel
RUN docker-php-ext-install pdo pdo_mysql pdo_pgsql opcache 
# Thêm các extension khác nếu cần, ví dụ: bcmath, gd...

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Thiết lập thư mục làm việc
WORKDIR /var/www

# Sao chép code ứng dụng vào container
COPY . /var/www

# Cấu hình Nginx (Đơn giản)
COPY .docker/nginx.conf /etc/nginx/conf.d/default.conf

# Cài đặt các dependencies
RUN composer install --no-dev --optimize-autoloader

# Thiết lập quyền truy cập
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 /var/www/storage

# Expose cổng mà Nginx sẽ lắng nghe
EXPOSE 80

# Định nghĩa lệnh khởi động
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]