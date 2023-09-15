<?php
require 'config.php';
require_once __DIR__ . '/../database_config.php';

class LoginController {
    public function renderLoginPage() {
        // Incluez le fichier HTML de la page de connexion
        include __DIR__ . '/../../views/User/login.html';
    }

    public function login() {
        // Récupérez les données du formulaire
        $email = $_POST['_email'];
        $password = $_POST['_password'];
    
        // Obtenez la connexion à la base de données
        $pdo = connect();
    
        // Recherchez l'utilisateur en fonction de l'e-mail
        $stmt = $pdo->prepare("SELECT id, email, password FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            $_SESSION['error_message'] = "Le compte n'existe pas. Veuillez vérifier votre adresse e-mail.";
            header('Location: login'); // Redirigez vers la page de connexion
            exit();
        }
    
        // Vérifiez le mot de passe
        if (password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: home');
            exit();
        } else {
            // Mot de passe incorrect, gérer l'erreur
            echo "Mot de passe incorrect.";
            return;
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header('Location: home');
        exit();
    }
    
}
