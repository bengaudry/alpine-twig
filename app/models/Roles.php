<?php

require_once 'db/Database.php';
require_once 'lib/Logger.php';

class Roles
{
    public static function getAll() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                SELECT 
                    R.id,
                    R.nom_role,
                    R.description
                FROM roles R
            SQL);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e);
            return [];
        }
    }

    /**
     * Vérifie si un utilisateur est administrateur.
     */
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
     * Active ou désactive un rôle pour un utilisateur donné.
     */
    public static function toggleUserRole(string $userId, string $roleId): bool {
        if (!isset($userId) || !isset($roleId)) return false;
        try {
            $db = Database::getInstance()->getConnection();

            // On récupère dans la base de données si l'utilisateur a déjà ce rôle
            $stmt = $db->prepare(<<<SQL
                SELECT role_id 
                FROM role_user
                WHERE user_id = :user_id AND role_id = :role_id
            SQL);
            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":role_id", $roleId);
            $stmt->execute();
            $existingRole = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log(json_encode($existingRole));

            if ($existingRole) {
                // Le rôle existe déjà, on le supprime
                $stmt = $db->prepare(<<<SQL
                    DELETE FROM role_user
                    WHERE user_id = :user_id AND role_id = :role_id
                SQL);
                Logger::getInstance()->log("Suppression du rôle {$roleId} pour l'utilisateur {$userId}");
            } else {
                // Le rôle n'existe pas, on l'ajoute
                $stmt = $db->prepare(<<<SQL
                    INSERT INTO role_user (user_id, role_id)
                    VALUES (:user_id, :role_id)
                SQL);
                Logger::getInstance()->log("Ajout du rôle {$roleId} pour l'utilisateur {$userId}");
            }

            $stmt->bindParam(":user_id", $userId);
            $stmt->bindParam(":role_id", $roleId);
            $stmt->execute();


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e);
            return false;
        }
    }
}
