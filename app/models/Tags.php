<?php

class Tags {

    private string $nom_tags;
    private string $slug;
    private ?int $id = null;
    public function __construct(string $nom_tags, $slug, ?int $id = null) {
        $this->nom_tags = $nom_tags;
        $this->slug = $slug;
        $this->id = $id;
    }
    public function getNomTags(): string {
        return $this->nom_tags;
    }
    public function getSlug(): string {
        return $this->slug;
    }
    public function getId(): ?int {
        return $this->id;
    }
    public function setNomTags(string $nom_tags): void {
        $this->nom_tags = $nom_tags;
        $this->slug = self::generateSlug($nom_tags);
    }
    private static function generateSlug(string $nom_tags): string {
        $slug = strtolower(trim($nom_tags));
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        return trim($slug, '-');
    }
    public static function findAll(PDO $pdo): array
    {
        $sql = 'SELECT * FROM tags ORDER BY nom_tag DESC';
        $stmt = $pdo->query($sql);
        $tags = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $tags[] = new Tags($row['nom_tag'], $row['slug'], $row['id']);
        }
        return $tags;
    }
    public static function find(PDO $pdo, int $id): ?Tags {
        $sql = 'SELECT * FROM tags WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row){
            return new Tags($row['nom_tag'], $row['slug'], $row['id']);
        }
        return null;
    }
    public function save(PDO $pdo): bool {
        if ($this->id == null) {
            $sql = 'INSERT INTO tags (nom_tag, slug) VALUES (:nom_tag, :slug)';
            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute([
                'nom_tag' => $this->nom_tags,
                'slug' => $this->slug
            ]);
            if ($res) {
                $this->id = (int)$pdo->lastInsertId();
                return true;
            }
        }
        else {
            $sql = 'UPDATE tags SET nom_tag = :nom_tag, slug = :slug WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            return $stmt->execute([
                'nom_tag' => $this->nom_tags,
                'slug' => $this->slug,
                'id' => $this->id
            ]);
        }
        return false;
    }
    public function delete(PDO $pdo): bool {
        if ($this->id == null) {
            return false;
        }
        $sql = 'DELETE FROM tags WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        return $stmt->execute(['id' => $this->id]);
    }
}