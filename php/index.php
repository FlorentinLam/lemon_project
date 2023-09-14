<?php
// Incluez ici votre logique pour initialiser l'application, par exemple, la connexion à la base de données ou la gestion de l'authentification.

// Incluez le fichier de configuration global
include __DIR__ . '/config.php';

// Gérez le routage ici
$requestUri = $_SERVER['REQUEST_URI'];

if ($requestUri === '/' || $requestUri === '/index.php') {
    include __DIR__ . '/../views/home/home.html';
} elseif ($requestUri === '/favorites.php') {
    // Incluez la vue des favoris depuis le répertoire views
    include __DIR__ . '/../views/favorites/favorites.html';
} elseif ($requestUri === '/movie_detail.php') {
    // Gérez l'affichage des détails d'un film
    // Vous devrez extraire les paramètres de l'URL, récupérer les données du film, puis afficher la vue appropriée.
} else {
    // Page non trouvée, affichez une erreur 404 par exemple.
    http_response_code(404);
    include __DIR__ . '/../views/errors/404.html';
}
