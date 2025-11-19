<?php

require_once 'db/Database.php';

class Articles {
  public function __construct() {}

  public function getArticles() {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare("SELECT slug, image_une, titre FROM articles");
      $stmt->execute();
      $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return ["articles" => $articles];
    } catch (PDOException $e) {
      error_log($e);
      return [];
    }
  }

  public function getArticle(string $slug) {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare("SELECT image_une, titre, date_creation, contenu FROM articles WHERE slug = :slug LIMIT 1");
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
