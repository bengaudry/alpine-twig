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


    public static function getAll() {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT C.id, C.nom_auteur, C.email_auteur, C.contenu, C.date_commentaire, C.statut,
                       C.article_id, A.slug as article_slug, A.titre as article_titre
                FROM Commentaires C
                INNER JOIN Articles A ON C.article_id = A.id
                ORDER BY C.date_commentaire DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log($e);
            return [];
        }
    }


    public static function countPendingComments(): int {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                SELECT COUNT(*) as total
                FROM Commentaires
                WHERE statut = 'En attente'
            ");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log($e);
            return 0;
        }
    }


    public static function approveComment(int $commentId): void {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                UPDATE Commentaires
                SET statut = 'Approuvé'
                WHERE id = :commentId
            ");
            $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e);
        }
    }


    public static function rejectComment(int $commentId): void {
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare("
                UPDATE Commentaires
                SET statut = 'Rejeté'
                WHERE id = :commentId
            ");
            $stmt->bindParam(':commentId', $commentId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log($e);
        }
    }
}
