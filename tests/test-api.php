<?php
require_once __DIR__ . '/../config.php'; // Assurez-vous d'ajuster le chemin en fonction de votre structure de dossiers
require_once __DIR__ . '/../php/API/MoviesDatabaseApiService.php'; // Assurez-vous d'ajuster le chemin en fonction de votre structure de dossiers

// Remplacez 'YOUR_API_KEY' par votre clé API réelle
$apiKey = '3e2be72ab9da08959f7519a4454eb029';

// Instanciez la classe MoviesDatabaseApiService
$apiService = new MoviesDatabaseApiService($apiKey);

// Appelez la méthode getPopularMovies
$popularMoviesResult = $apiService->getPopularMovies();

// Affichez les résultats pour vérification
var_dump($popularMoviesResult);
