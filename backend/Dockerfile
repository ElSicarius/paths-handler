# On part d'une image PHP officielle avec extensions nécessaires
FROM php:8.2-cli

# Installe les dépendances systèmes
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql

# Installation de Composer
COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

# Création du dossier de l'app
WORKDIR /var/www

RUN mkdir -p storage/framework/views

# Copie des fichiers de l'application
COPY . /var/www

# Installation des dépendances PHP (Lumen, etc.)
RUN composer install --optimize-autoloader --no-interaction

RUN echo "expose_php=Off" > /usr/local/etc/php/conf.d/hidepoweredby.ini

# Permission si nécessaire (sur dev)
RUN chown -R www-data:www-data /var/www

# Expose le port interne
EXPOSE 8000

# Lancement de l'application (serveur interne de Lumen)
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]
