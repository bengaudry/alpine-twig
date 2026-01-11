<?php

class Tags {
    public static function findAll(): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            SELECT T.id, T.nom_tag, T.slug, COUNT(AT.article_id) AS nb_articles
            FROM tags T
            LEFT JOIN article_tag AT ON T.id = AT.tag_id
            GROUP BY T.id
            ORDER BY T.nom_tag ASC;
            SQL);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function find(int $id): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            SELECT T.id, T.nom_tag, T.slug
            FROM Tags T 
            LEFT JOIN Article_Tag AT ON T.id = AT.tag_id
            WHERE AT.article_id = :id
            GROUP BY T.id
            SQL);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function create(string $nomTag, ?string $slug = null): ?int {
        if ($slug === null) {
            $slug = self::generateSlug($nomTag);
        }
        try {
            $db = Database::getInstance()->getConnection();
            $stmt = $db->prepare(<<<SQL
                INSERT INTO Tags (nom_tag, slug) VALUES (:nom, :slug)
                SQL);
            $success = $stmt->execute([
                'nom' => $nomTag,
                'slug' => $slug
            ]);

            return $success ? (int)$db->lastInsertId() : false;
        } catch (PDOException $e) {
            Logger::getInstance()->log("Erreur lors de la création du tag : " . $e->getMessage());
            return null;
        }
    }
    public static function update(int $id, string $nomTag, ?string $slug = null): bool {
        if ($slug === null) {
            $slug = self::generateSlug($nomTag);
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            UPDATE Tags SET nom_tag = :nom, slug = :slug WHERE id = :id
            SQL);
        return $stmt->execute([
            'nom' => $nomTag,
            'slug' => $slug,
            'id' => $id
        ]);
    }

    public static function delete(int $id): bool {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            DELETE FROM Tags WHERE id = :id
            SQL);
        return $stmt->execute(['id' => $id]);
    }

    public static function generateSlug(string $string): string {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }

    public static function getArticleTags() {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            SELECT AT.article_id, T.id as tag_id, T.nom_tag, T.slug
            FROM article_tag AT
            INNER JOIN tags T ON AT.tag_id = T.id
        SQL);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $articleTags = [];
        foreach ($result as $row) {
            $articleId = $row['article_id'];
            if (!isset($articleTags[$articleId])) {
                $articleTags[$articleId] = [];
            }
            $articleTags[$articleId][] = [
                'tag_id' => $row['tag_id'],
                'nom_tag' => $row['nom_tag'],
                'slug' => $row['slug']
            ];
        }
        return $articleTags;
    }
}
