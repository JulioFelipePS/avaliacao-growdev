# Usar a imagem oficial do PHP com Apache
FROM php:8.2-apache

# Instalar extensões necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip \
    && docker-php-ext-install pdo_mysql zip

# Instalar o Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www/html

# Copiar os arquivos do Laravel para o contêiner
COPY . /var/www/html

# Configurar permissões
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Habilitar mod_rewrite do Apache
RUN a2enmod rewrite

# Configurar a porta padrão
EXPOSE 80

# Comando padrão para iniciar o Apache
CMD ["apache2-foreground"]

# Executar as migrações
CMD php artisan migrate --force && apache2-foreground
