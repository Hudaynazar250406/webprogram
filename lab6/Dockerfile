# Dockerfile для запуска PHP приложения
FROM php:8.1-apache

# Копируем файлы приложения
COPY . /var/www/html/

# Устанавливаем права
RUN chown -R www-data:www-data /var/www/html

# Включаем mod_rewrite
RUN a2enmod rewrite

# Открываем порт 80
EXPOSE 80
