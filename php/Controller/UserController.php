<?php
require 'config.php';
require_once __DIR__ . '/../database_config.php';

class UserController {

    public function renderRegister() {
        // Incluez le fichier HTML de la page de connexion
        include __DIR__ . '/../../views/User/register.html';
    }

    public function register() {
        // Récupérez les données du formulaire POST
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address'];
    
        // Vérifiez si l'utilisateur avec l'adresse e-mail donnée existe déjà
        $pdo = connect();
        $stmt = $pdo->prepare("SELECT id FROM user WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingUser) {
            // L'utilisateur existe déjà, affichez un message d'erreur ou redirigez-le vers une page d'erreur
            echo "Un utilisateur avec cette adresse e-mail existe déjà.";
            return;
        }
    
        // Hachez le mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
        // Insérez les données de l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO user (first_name, last_name, email, password, address, created_at, updated_at) 
                                VALUES (:firstName, :lastName, :email, :password, :address, NOW(), NOW())");
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':address', $address);
        $stmt->execute();
    
        // Redirigez l'utilisateur vers la page de connexion ou toute autre page appropriée
        header('Location: login'); // Redirigez vers la page de connexion après l'inscription
        exit();
    }
    
}
