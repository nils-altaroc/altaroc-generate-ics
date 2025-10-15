# Utilise l’image officielle PHP
FROM php:8.2-cli

# Copie tous les fichiers du repo dans /app
WORKDIR /app
COPY . /app

# Affiche la liste des fichiers copiés (pour debug)
RUN ls -la /app

# Lance le serveur PHP intégré
CMD php -S 0.0.0.0:10000 -t /app
