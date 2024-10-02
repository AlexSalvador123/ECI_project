FROM laravelphp/php-fpm:8.3

# Instalar extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql
