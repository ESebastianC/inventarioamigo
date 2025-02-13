# Usa una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instala extensiones necesarias
RUN docker-php-ext-install pdo pdo_mysql

# Copia los archivos del proyecto al servidor Apache
COPY . /var/www/html/

# Da permisos adecuados
RUN chown -R www-data:www-data /var/www/html

# Cambiar el archivo de inicio por login.php
RUN echo "DirectoryIndex login.php" > /etc/apache2/mods-enabled/dir.conf

# Expone el puerto 80
EXPOSE 80

# Inicia Apache cuando el contenedor se ejecuta
CMD ["apache2-foreground"]
