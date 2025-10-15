# Utilise une image PHP officielle
FROM php:8.2-cli

# Copie les fichiers dans le conteneur
COPY . /app
WORKDIR /app

# Lance un serveur PHP intégré sur le port 10000
CMD ["php", "-S", "0.0.0.0:10000"]
