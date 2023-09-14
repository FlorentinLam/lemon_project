<?php
// Active l'affichage des erreurs PHP (pour le débogage)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluez le fichier de configuration global
include __DIR__ . '/config.php';
require_once __DIR__ . '/php/Controller/MovieController.php';

$controller = new MovieController($apiKey);
$controller->renderHomePage();

// Redirige toujours vers home.html
