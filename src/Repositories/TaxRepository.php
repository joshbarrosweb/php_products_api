<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Tax;
use App\Interfaces\TaxRepositoryInterface;

class TaxRepository implements TaxRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM taxes";
        $statement = $connection->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?Tax
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM taxes WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $taxData = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$taxData) {
            return null;
        }

        return new Tax(
            $taxData['id'],
            $taxData['name'],
            $taxData['rate'],
            $taxData['created_at']
        );
    }

    public function create(Tax $tax): void
    {
        $connection = $this->database->getConnection();
        $query = "INSERT INTO taxes (name, rate, created_at) VALUES (:name, :rate, :created_at)";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $tax->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':rate', $tax->getRate(), \PDO::PARAM_STR);
        $statement->bindValue(':created_at', $tax->getCreatedAt(), \PDO::PARAM_STR);
        $statement->execute();
    }

    public function update(Tax $tax): void
    {
        $connection = $this->database->getConnection();
        $query = "UPDATE taxes SET name = :name, rate = :rate WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $tax->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':rate', $tax->getRate(), \PDO::PARAM_STR);
        $statement->bindValue(':id', $tax->getId(), \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $connection = $this->database->getConnection();
        $query = "DELETE FROM taxes WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
