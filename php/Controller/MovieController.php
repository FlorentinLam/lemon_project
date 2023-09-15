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

    public function renderMovieDetails($movieId) {
        // Récupérez les détails du film en fonction de $movieId depuis votre source de données
        $movieDetails = $this->apiService->getMovieDetails($movieId); 
    
        // Vérifiez si vous avez réussi à récupérer les détails du film
        if ($movieDetails) {
            include __DIR__ . '/../../views/movie/details.html'; // Assurez-vous que votre vue s'appelle 'movie_details.php'
        } else {
            echo "Détails du film introuvables.";
        }
    }

    public function addMovieToFavorite($movieId) {
        $pdo = connect();
        $user_id = $_SESSION['user_id']; // Récupérez l'ID de l'utilisateur à partir de la session
        $stmt = $pdo->prepare("INSERT INTO favorite_movies (user_id, movie_id) VALUES (:userId, :movieId)");

        $stmt->bindParam(':userId', $user_id);  
        $stmt->bindParam(':movieId', $movieId);
        $stmt->execute();
        header('Location: home');
        exit();

    }
    
}
