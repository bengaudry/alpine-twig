<?php

require_once 'db/Database.php';

class Comments {
    public static function getByArticleId(int $articleId): array {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT nom_auteur AS username, email_auteur, contenu AS content, date_commentaire AS created_at
                FROM Commentaires
                WHERE article_id = :articleId
                  AND statut = 'Approuvé'
                ORDER BY date_commentaire ASC
            ");
            $stmt->bindParam(':articleId', $articleId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e);
            return [];
        }
    }
}
