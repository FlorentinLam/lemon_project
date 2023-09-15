<?php
// Active l'affichage des erreurs PHP (pour le débogage)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluez le fichier de configuration global
include __DIR__ . '/config.php';
require_once(__DIR__ . '/php/database_config.php');
require_once __DIR__ . '/php/Controller/MovieController.php';
require_once __DIR__ . '/php/Controller/LoginController.php';

$logFilePath = __DIR__ . '/logs/debug.log';
$requestUri = $_SERVER['REQUEST_URI'];

// Fonction pour enregistrer un message de log
function logMessage($message) {
    global $logFilePath;
    file_put_contents($logFilePath, date('Y-m-d H:i:s') . ' ' . $message . PHP_EOL, FILE_APPEND);
}

logMessage("Request URI: " . $requestUri);

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        $controller = new MovieController($apiKey);
        $controller->renderHomePage();
        break;
    case 'login':
        $controller = new LoginController();
        $controller->renderLoginPage();
        break;
    default:
        echo 'Page non trouvée';
}




