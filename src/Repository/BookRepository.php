<?php
namespace App\Repository;

use PDO;

class BookRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM books WHERE itemPrice <= 100 ORDER BY itemPrice ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
