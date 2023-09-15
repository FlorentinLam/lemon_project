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
    
        if ($query) {
            $movies = $this->apiService->searchMovies($query);
        } else {
            $movies = null;
        }
        
        include __DIR__ . '/../../views/movie/search.html';
    }
    
}