<?php

require_once 'db/Database.php';

class Articles {

  public static function getArticles() {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare(<<<SQL
        SELECT
          A.id, A.slug, A.image_une, A.titre, A.date_creation,
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
}
