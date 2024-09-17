# Utiliser une image de base PHP avec Apache
FROM php:7.4-apache

# Installer les extensions PHP nécessaires
RUN docker-php-ext-install mysqli

# Copier les fichiers de l'application dans le conteneur
COPY . /var/www/html/

# Exposer le port 80
EXPOSE 80

# Commande par défaut pour démarrer le serveur Apache
CMD ["apache2-foreground"]
