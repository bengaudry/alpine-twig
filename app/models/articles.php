<?php

require_once 'db/Database.php';

class Articles {
  public function __construct() {}

  public function getArticles() {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare("SELECT id, imageUrl, titre, resume FROM articles LIMIT 50");
      $stmt->execute();
      $articles = $stmt->fetchAll(PDO::FETCH_ASSOC);
      return ["articles" => $articles];
    } catch (PDOException $e) {
      error_log($e);
      return [];
    }
  }

  public function getArticle(string $id) {
    try  {
      $db = Database::getInstance()->getConnection();
      $stmt = $db->prepare("SELECT imageUrl, titre, date, resume, contenu FROM articles WHERE id = :id LIMIT 1");
      $stmt->bindParam(":id", $id);
      $stmt->execute();
      $article = $stmt->fetch(PDO::FETCH_ASSOC);
      return $article;
    } catch (PDOException $e) {
      error_log($e);
      return null;
    }
  }
}
