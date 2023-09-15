<?php 
require 'config.php';
require_once __DIR__ . '/../API/MoviesDatabaseApiService.php';

class SearchController {
    private $apiService;

    public function __construct(string $apiKey) {
        $this->apiService = new MoviesDatabaseApiService($apiKey);
    }

    public function renderSearchPage() {
        $query = $_GET['keyword'] ?? null;

        $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'anonyme';
        $this->logSearch($user_id, $query);
            
        if ($query) {
            $movies = $this->apiService->searchMovies($query);
        } else {
            $movies = null;
        }
        
        include __DIR__ . '/../../views/movie/search.html';
    }

    public function logSearch($user_id, $query) {

        $dateTime = date("Y-m-d H:i:s");
        $pdo = connect();
        $stmt = $pdo->prepare("SELECT first_name FROM user WHERE id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $username = $stmt->fetchColumn();
        
        // Formattez le message du journal
        $logMessage = "[$dateTime] Utilisateur: $username, Recherche: $query\n";
        
        // Chemin vers le fichier journal
        $logFile = 'logs/search.log';

        // Écrivez le message dans le fichier journal
        file_put_contents($logFile, $logMessage, FILE_APPEND);

    }
    
}