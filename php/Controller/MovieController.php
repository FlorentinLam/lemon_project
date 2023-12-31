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

        $user_id = $_SESSION['user_id']; 
        $movieDetails = $this->apiService->getMovieDetails($movieId);
        $isFavorite = $this->isMovieInFavorites($user_id, $movieId); 
    
        // Vérifiez si vous avez réussi à récupérer les détails du film
        if ($movieDetails) {
            include __DIR__ . '/../../views/movie/details.html'; // Assurez-vous que votre vue s'appelle 'movie_details.php'
        } else {
            echo "Détails du film introuvables.";
        }
    }

    public function isMovieInFavorites($userId, $movieId) {
        $pdo = connect();
    
        // Requête pour vérifier si le film existe dans les favoris de l'utilisateur
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM favorite WHERE user_id = :userId AND film_id = :movieId");
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':movieId', $movieId);
        $stmt->execute();
    
        // Récupérez le résultat de la requête
        $count = $stmt->fetchColumn();
    
        // Si $count est supérieur à zéro, le film est dans les favoris de l'utilisateur
        return $count > 0;
    }
    

    public function renderFavoriteMovies() {
        $user_id = $_SESSION['user_id']; // Récupérez l'ID de l'utilisateur à partir de la session
        if(!isset($user_id)) {
            header('Location: login');
            exit();
        }

        $pdo = connect();
    
        $stmt = $pdo->prepare("SELECT film_id FROM favorite WHERE user_id = :userId");
        $stmt->bindParam(':userId', $user_id);
        $stmt->execute();
    
        $favoriteMovies = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $favoriteMoviesIds = array_column($favoriteMovies, 'film_id');
    
        $favoriteMoviesDetails = $this->apiService->getFavoriteMoviesDetails($favoriteMoviesIds);
    
        include __DIR__ . '/../../views/movie/favorite.html';
    }
    
    public function addMovieToFavorite($movieId) {
        // Vérifiez si une demande POST a été soumise
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérez l'ID du film depuis le formulaire
            if (isset($_POST['movie_id'])) {
                $movieId = $_POST['movie_id'];
                
                // Récupérez l'ID de l'utilisateur à partir de la session
                $userId = $_SESSION['user_id'];
                
                // Ajoutez le film aux favoris
                $pdo = connect();
                $stmt = $pdo->prepare("INSERT INTO favorite (user_id, film_id) VALUES (:userId, :movieId)");
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':movieId', $movieId);
                $stmt->execute();
                
                header('Location: favorite');
                exit();
            }
        }
    }

    public function removeMovieFromFavorite($movieId) {
        // Vérifiez si une demande POST a été soumise
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Récupérez l'ID du film depuis le formulaire
            if (isset($_POST['movie_id'])) {

                $movieId = $_POST['movie_id'];
                
                // Récupérez l'ID de l'utilisateur à partir de la session
                $userId = $_SESSION['user_id'];
                
                // Supprimez le film des favoris
                $pdo = connect();
                $stmt = $pdo->prepare("DELETE FROM favorite WHERE user_id = :userId AND film_id = :movieId");
                $stmt->bindParam(':userId', $userId);
                $stmt->bindParam(':movieId', $movieId);
                $stmt->execute();
                
                header('Location: favorite');
                exit();
            }
        }
    }
}
