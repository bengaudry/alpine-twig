<?php

require_once 'db/Database.php';
require_once 'lib/Logger.php';

class Users {
    
    /**
     * Retourne une liste de tous les utilisateurs
     */
    public static function getAll() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                SELECT 
                    U.id, 
                    U.nom_utilisateur, 
                    U.email, 
                    U.est_actif, 
                    U.date_inscription,
                    GROUP_CONCAT(R.nom_role SEPARATOR ', ') as roles
                FROM utilisateurs U
                INNER JOIN role_user RU ON RU.user_id = U.id
                INNER JOIN roles R ON R.id = RU.role_id
                GROUP BY U.id, U.nom_utilisateur, U.email, U.est_actif, U.date_inscription
                ORDER BY U.date_inscription DESC
            SQL);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return $users;
        } catch (PDOException $e) {
            error_log($e);
            return null;
        }
    }
    
    public static function isAdmin(string $userId): bool {
        if (!isset($userId)) return false;
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                SELECT 
                    RU.role_id
                FROM utilisateurs U
                INNER JOIN role_user RU ON RU.user_id = U.id
                WHERE U.id = :id
            SQL);
            $stmt->bindParam(":id", $userId);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            return is_array($data['role_id'])
                ? in_array(1, $data['role_id'])
                : $data['role_id'] == 1;
        } catch (PDOException $e) {
            error_log($e);
            return false;
        }
    }


    /**
     * Active un utilisateur si il est désactivé et vice-versa
     */
    public static function toggleActivation(string $userId) {
        if (!isset($userId)) return;
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                SELECT est_actif
                FROM utilisateurs
                WHERE id = :id
            SQL);
            $stmt->bindParam(":id", $userId);
            $stmt->execute();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            
            $stmt = $db->prepare(<<<SQL
                UPDATE utilisateurs 
                SET est_actif = :active
                WHERE id = :id
            SQL);
            $newActiveState = 1 - $data['est_actif'];
            $stmt->bindParam(":active", $newActiveState);
            $stmt->bindParam(":id", $userId);
            $stmt->execute();

            Logger::getInstance()->log(
                "Changement du statut d'activation de l'utilisateur d'id "
                . $userId . " à : " . strval($newActiveState)
            );
        } catch (PDOException $e) {
            error_log($e);
            return;
        }
    }

}
