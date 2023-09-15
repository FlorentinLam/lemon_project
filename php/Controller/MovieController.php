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

            $randomMovieId = $randomMoviesResult['results'][0]['id'];

            $videos = $this->apiService->getMovieVideos($randomMovieId);
            if  (!empty($videos['results'])) {
                // Affichez la première vidéo (ou la vidéo de votre choix)
                $videoKey = $videos['results'][0]['key'];
                }
        }
        
        include __DIR__ . '/../../views/home/home.html';
    }
}
