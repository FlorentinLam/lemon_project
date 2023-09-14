<?php
require 'config.php';
require_once __DIR__ . '/../API/MoviesDatabaseApiService.php';


class MovieController {
    private $apiService;

    public function __construct(string $apiKey) {
        $this->apiService = new MoviesDatabaseApiService($apiKey);
    }

    public function renderHomePage() {
        $popularMoviesResult = $this->apiService->getPopularMovies();
        $randomMoviesResult = $this->apiService->getRandomMovies();
        // Vérifiez que des résultats sont disponibles
        if (!empty($randomMoviesResult['results'])) {
            // Mélangez les résultats de manière aléatoire
            shuffle($randomMoviesResult['results']);
        }
        
        include __DIR__ . '/../../views/home/home.html';
    }
}
