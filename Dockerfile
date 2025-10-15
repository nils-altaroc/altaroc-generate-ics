# Utilise PHP 8.2
FROM php:8.2-cli

# Copie tout le code dans /app
WORKDIR /app
COPY . /app

# Assure-toi que le fichier existe bien
RUN ls -la /app

# Démarre le serveur PHP intégré sur le port 10000
CMD ["php", "-S", "0.0.0.]()
