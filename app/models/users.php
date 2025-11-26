<?php

require_once 'db/Database.php';

class Users {
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
}
