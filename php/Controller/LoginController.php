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
        $email = $_POST['email'];
        $password = $_POST['password'];
    
        // Obtenez la connexion à la base de données
        $pdo = connect();
    
        // Recherchez l'utilisateur en fonction de l'e-mail
        $stmt = $pdo->prepare("SELECT id, email, password FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if (!$user) {
            // L'utilisateur n'existe pas, gérer l'erreur
            // Par exemple, affichez un message d'erreur et redirigez vers la page de connexion
            echo "L'utilisateur n'existe pas.";
            return;
        }
    
        // Vérifiez le mot de passe
        if (password_verify($password, $user['password'])) {
            // Mot de passe correct, connectez l'utilisateur
            // Vous pouvez gérer la session de l'utilisateur ici
            // Par exemple, utilisez $_SESSION pour stocker des informations d'authentification
            session_start();
            $_SESSION['user_id'] = $user['id'];
            // Redirigez l'utilisateur vers la page d'accueil ou toute autre page appropriée
            header('Location: /home');
            exit();
        } else {
            // Mot de passe incorrect, gérer l'erreur
            // Par exemple, affichez un message d'erreur et redirigez vers la page de connexion
            echo "Mot de passe incorrect.";
            return;
        }
    }
    
}
