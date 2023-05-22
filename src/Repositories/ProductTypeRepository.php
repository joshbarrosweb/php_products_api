<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\ProductType;
use App\Interfaces\ProductTypeRepositoryInterface;

class ProductTypeRepository implements ProductTypeRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM product_types";
        $statement = $connection->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?ProductType
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM product_types WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $productTypeData = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$productTypeData) {
            return null;
        }

        return new ProductType(
            (int)$productTypeData['id'],
            $productTypeData['name'],
            $productTypeData['created_at']
        );
    }

    public function create(ProductType $productType): void
    {
        $connection = $this->database->getConnection();
        $query = "INSERT INTO product_types (name, created_at) VALUES (:name, :created_at)";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $productType->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':created_at', $productType->getCreatedAt(), \PDO::PARAM_STR);
        $statement->execute();

        $id = $connection->lastInsertId();
        $productType->setId((int)$id);
    }

    public function update(ProductType $productType): void
    {
        $connection = $this->database->getConnection();
        $query = "UPDATE product_types SET name = :name WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $productType->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':id', $productType->getId(), \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $connection = $this->database->getConnection();
        $query = "DELETE FROM product_types WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
