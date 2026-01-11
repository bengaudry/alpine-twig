<?php

class Tags {
    public static function findAll(PDO $pdo): array
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            SELECT * FROM tags ORDER BY nom_tags ASC;
            SQL);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    public static function find(PDO $pdo, int $id): ?array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            SELECT * FROM tags WHERE id = :id
            SQL);
        $stmt->execute(['id' => $id]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function create(PDO $pdo, string $nomTag, ?string $slug = null): ?int {
        if ($slug === null) {
            $slug = self::generateSlug($nomTag);
        }
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            INSERT INTO Tags (nom_tag, slug) VALUES (:nom, :slug)
            SQL);
        try {
            $success = $stmt->execute([
                'nom' => $nomTag,
                'slug' => $slug
            ]);

            return $success ? (int)$pdo->lastInsertId() : false;
        } catch (PDOException $e) {
            return null;
        }
    }
    public static function update(PDO $pdo, int $id, string $nomTag, ?string $slug = null): bool {
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

    public static function delete(PDO $pdo): array {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare(<<<SQL
            DELETE FROM Tags WHERE id = :id
            SQL);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function generateSlug(string $string): string {
        $slug = strtolower(trim($string));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
}