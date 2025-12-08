<?php 

require_once 'lib/Logger.php';
require_once 'lib/SessionManager.php';
require_once 'db/Database.php';

class Auth {

    /**
     * Procédure de connexion d'un utilisateur
     */
    public static function signIn(string $email, string $password)
    {
        $logger = Logger::getInstance();
        $db = Database::getInstance()->getConnection();
        $session = SessionManager::getInstance();

        try {
            // Récupération des données de l'utilisateur
            $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Rediriger si le compte de l'utilisateur à été désactivé
            if (!$user['est_actif']) {
                Auth::redirectWithError("/signin", "Compte désactivé");
            };

            // Vérification du mot de passe
            if ($user && password_verify($password, $user['mot_de_passe'])) {
                $session->set('user_id', $user['id']);
                $session->set('username', $user['nom_utilisateur']);
                $session->set('email', $user['email']);

                $logger->log("Connexion réussie pour {$user['nom_utilisateur']}");

                header('Location: /profile');
                exit;
            } else {
                $logger->log("Échec de connexion pour $email");
                Auth::redirectWithError("/signin", "Identifiants invalides.");
            }
        } catch (PDOException $e) {
            $logger->log("Erreur PDO : " . $e->getMessage());
            Auth::redirectWithError("/signin", "Erreur de base de données.");
        }
    }


    /**
     * Crée un compte utilisateur
     */
    public static function register(string $name, string $email, string $password)
    {
        $logger = Logger::getInstance();
        $db = Database::getInstance()->getConnection();
        $session = SessionManager::getInstance();
        
        // Vérification de l'email
        if (strlen($name) < 3) {
            Auth::redirectWithError(
                "/register", 
                "Le nom d'utilisateur doit contenir au moins 3 caractères"
            );
        }
        
        // Vérification de l'email
        if (strlen($password) < 6) {
            Auth::redirectWithError(
                "/register", 
                "Le mot de passe doit contenir au moins 6 caractères"
            );
        }
        
        // Vérification de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Auth::redirectWithError(
                "/register", 
                "Le format de l'adresse email est incorrect"
            );
        }

        try {
            $hash = password_hash($password, null);

            // Insertion de l'utilisateur dans la db
            $stmt = $db->prepare("INSERT INTO utilisateurs (nom_utilisateur, email, mot_de_passe) VALUES (:username, :email, :pass_hash)");
            $stmt->bindParam(':username', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':pass_hash', $hash);
            $stmt->execute();
            $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Récupération de l'id de l'utilisateur créé
            $stmt = $db->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !isset($user['id'])) {
                throw new PDOException("Could not retreive user id");
            }

            // Mise à jour des données de session
            $session->set('user_id', $user['id']);
            $session->set('username', $name);
            $session->set('email', $email);
            $logger->log("Inscription réussie pour {$name} ({$email})");
            header('Location: /profile');
            exit;
        } catch (PDOException $e) {
            $logger->log("Erreur PDO : " . $e->getMessage());
            Auth::redirectWithError("/register", "Erreur de base de données.");
        }
    }


    /**
     * Redirige vers l'url en paramètre avec une erreur en GET
     */
    private static function redirectWithError(string $url, string $message)
    {
        $redirect_url = $url . '?error=' . urlencode($message);
        Logger::getInstance()->log("Redirection vers " . $redirect_url . " avec l'erreur : " . $message);
        header('Location: ' . $redirect_url);
        exit;
    }

}
