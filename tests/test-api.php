<?php
require_once __DIR__ . '/../config.php'; // Assurez-vous d'ajuster le chemin en fonction de votre structure de dossiers
require_once __DIR__ . '/../php/API/MoviesDatabaseApiService.php'; // Assurez-vous d'ajuster le chemin en fonction de votre structure de dossiers

$apiKey = '3e2be72ab9da08959f7519a4454eb029';

$apiService = new MoviesDatabaseApiService($apiKey);

// $popularMoviesResult = $apiService->getPopularMovies();
$movieDetails = $apiService->getMovieDetails(979275);

// Affichez les résultats pour vérification
var_dump($movieDetails);
