# Usa una imagen oficial de PHP con servidor embebido
FROM php:8.2-apache

# Copia todos los archivos del proyecto al contenedor
COPY . /var/www/html/

# Expone el puerto 8080 (Render lo usa internamente)
EXPOSE 8080

# Cambia el DocumentRoot de Apache al directorio actual
RUN sed -i 's!/var/www/html!/var/www/html!g' /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite por si usas rutas limpias
RUN a2enmod rewrite

# Inicia Apache
CMD ["apache2-foreground"]
