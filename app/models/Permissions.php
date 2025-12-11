<?php

class Permissions
{
    public static function userHasPermission(string $user_id, int $permission_id): bool {
        $user_roles = Roles::getUserRoles($user_id);
        if (!$user_roles) {
            error_log("No roles found for user ID: $user_id");
            return false;
        }

        foreach ($user_roles as $role) {
            error_log(json_encode($role['role_id']));
            if (self::roleHasPermission($role['role_id'], $permission_id)) {
                return true;
            }
        }
        return false;
    }

    public static function roleHasPermission(int $role_id, int $permission_id): bool {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                SELECT permission_id
                FROM role_permission
                WHERE role_id = :role_id AND permission_id = :permission_id
            SQL);
            $stmt->bindParam(':role_id', $role_id);
            $stmt->bindParam(':permission_id', $permission_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            error_log(json_encode($result));

            return $result !== false;
        } catch (PDOException $e) {
            Logger::getInstance()->log($e);
            return false;
        }
    }

    public static function hasAdminAccess(int $user_id) {
        return self::userHasPermission($user_id, 1);
    }

    public static function canCreateArticle(int $user_id) {
        return self::userHasPermission($user_id, 2);
    }

    public static function canEditAllArticles(int $user_id) {
        return self::userHasPermission($user_id, 3);
    }

    public static function canPublishArticle(int $user_id) {
        return self::userHasPermission($user_id, 4);
    }

    public static function canDeleteArticle(int $user_id) {
        return self::userHasPermission($user_id, 5);
    }

    public static function canManageComments(int $user_id) {
        return self::userHasPermission($user_id, 6);
    }

    public static function canManageUsers(int $user_id) {
        return self::userHasPermission($user_id, 7);
    }

    public static function canManageTags(int $user_id) {
        return self::userHasPermission($user_id, 8);
    }
}