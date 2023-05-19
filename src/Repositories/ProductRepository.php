<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Product;
use App\Interfaces\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM products";
        $statement = $connection->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?Product
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM products WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $productData = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$productData) {
            return null;
        }

        return new Product(
            $productData['id'],
            $productData['name'],
            $productData['price'],
            $productData['quantity'],
            $productData['created_at']
        );
    }

    public function create(Product $product): void
    {
        $connection = $this->database->getConnection();
        $query = "INSERT INTO products (name, price, quantity, created_at) VALUES (:name, :price, :quantity, :created_at)";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':price', $product->getPrice(), \PDO::PARAM_STR);
        $statement->bindValue(':quantity', $product->getQuantity(), \PDO::PARAM_INT);
        $statement->bindValue(':created_at', $product->getCreatedAt(), \PDO::PARAM_STR);
        $statement->execute();
    }

    public function update(Product $product): void
    {
        $connection = $this->database->getConnection();
        $query = "UPDATE products SET name = :name, price = :price, quantity = :quantity WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':name', $product->getName(), \PDO::PARAM_STR);
        $statement->bindValue(':price', $product->getPrice(), \PDO::PARAM_STR);
        $statement->bindValue(':quantity', $product->getQuantity(), \PDO::PARAM_INT);
        $statement->bindValue(':id', $product->getId(), \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $connection = $this->database->getConnection();
        $query = "DELETE FROM products WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
