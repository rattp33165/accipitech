FROM php:8.2-fpm

# ติดตั้ง System Dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    curl

# ติดตั้ง PHP Extensions ที่ Laravel ต้องใช้
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# ตั้งค่า Working Directory
WORKDIR /var/www

# Copy ไฟล์ทั้งหมด
COPY . .

# ติดตั้ง PHP dependencies
RUN composer install

# ให้สิทธิ์ Folder Storage (แก้ปัญหา Permission)
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
