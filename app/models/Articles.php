<?php

require_once 'db/Database.php';

class Articles {

  /**
   * Récupère tous les articles (sans le contenu)
   */
  public static function getAllArticles() {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        SELECT
          A.id, A.slug, A.image_une, A.titre, A.date_creation, A.statut,
          U.nom_utilisateur
        FROM articles A
        INNER JOIN utilisateurs U ON U.id = A.utilisateur_id
      SQL);
      $stmt->execute();
      $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return ["articles" => $articles];
    } catch (PDOException $e) {
      error_log($e);
      return ["articles" => []];
    }
  }


  public static function countPublishedArticles(): int {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        SELECT COUNT(*) as total
        FROM articles
        WHERE statut = 'Publié'
      SQL);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
      return $result['total'] ?? 0;
    } catch (PDOException $e) {
      error_log($e);
      return 0;
    }
  }
  
  
  /**
   * Récupère tous les articles (sans le contenu) ayant le statut 'Publié'
   */
  public static function getPublishedArticles() {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        SELECT
          A.id, A.slug, A.image_une, A.titre, A.date_creation,
          U.nom_utilisateur
        FROM articles A
        INNER JOIN utilisateurs U ON U.id = A.utilisateur_id
        WHERE statut = 'Publié'
      SQL);
      $stmt->execute();
      $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return ["articles" => $articles];
    } catch (PDOException $e) {
      error_log($e);
      return ["articles" => []];
    }
  }


  /**
   * Récupère les détails d'un article spécifique
   */
  public static function getArticle(string $slug) {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        SELECT
          A.image_une, A.titre, A.date_creation, A.contenu,
          U.nom_utilisateur
        FROM articles A
        INNER JOIN utilisateurs U ON U.id = A.utilisateur_id
        WHERE slug = :slug
        LIMIT 1
      SQL);
      $stmt->bindParam(":slug", $slug);
      $stmt->execute();
      $article = $stmt->fetch(PDO::FETCH_ASSOC);
      return $article;
    } catch (PDOException $e) {
      error_log($e);
      return null;
    }
  }


  /**
   * Supprime un article de la BDD
   */
  public static function deleteArticle(string $articleId) {
    try {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        DELETE FROM articles
        WHERE id = :id
      SQL);
      $stmt->bindParam(":id", $articleId);
      return $stmt->execute();
    } catch (PDOException $e) {
      error_log($e);
      return false;
    }
  }


  /**
   * Change le statut d'un article (Publié | Brouillon | Archivé)
   */
  private static function changeArticleStatus(string $articleId, string $status) {
    if (
      $status !== 'Brouillon'
      && $status !== 'Publié'
      && $status !== 'Archivé'
    ) return false;

    try {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        UPDATE articles
        SET statut = :status
        WHERE id = :id
      SQL);
      $stmt->bindParam(":status", $status);
      $stmt->bindParam(":id", $articleId);
      return $stmt->execute();
    } catch (PDOException $e) {
      error_log($e);
      return false;
    }
  }


  /**
   * Change le statut d'un article à 'Archivé'
   */
  public static function archiveArticle(string $articleId) {
    return Articles::changeArticleStatus($articleId, "Archivé");
  }
  

  /**
   * Change le statut d'un article à 'Publié'
   */
  public static function publishArticle(string $articleId) {
    return Articles::changeArticleStatus($articleId, "Publié");
  }

}
