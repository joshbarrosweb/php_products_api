<?php

namespace App\Repositories;

use App\Config\Database;
use App\Models\Sale;
use App\Interfaces\SaleRepositoryInterface;

class SaleRepository implements SaleRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function getAll(): array
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM sales";
        $statement = $connection->query($query);
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?Sale
    {
        $connection = $this->database->getConnection();
        $query = "SELECT * FROM sales WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $saleData = $statement->fetch(\PDO::FETCH_ASSOC);

        if (!$saleData) {
            return null;
        }

        return new Sale(
            $saleData['id'],
            $saleData['product_id'],
            $saleData['quantity'],
            $saleData['total'],
            $saleData['tax_amount'],
            $saleData['created_at']
        );
    }

    public function create(Sale $sale): void
    {
        $connection = $this->database->getConnection();
        $query = "INSERT INTO sales (product_id, quantity, total, tax_amount, created_at) VALUES (:product_id, :quantity, :total, :tax_amount, :created_at)";
        $statement = $connection->prepare($query);
        $statement->bindValue(':product_id', $sale->getProductId(), \PDO::PARAM_INT);
        $statement->bindValue(':quantity', $sale->getQuantity(), \PDO::PARAM_INT);
        $statement->bindValue(':total', $sale->getTotal(), \PDO::PARAM_STR);
        $statement->bindValue(':tax_amount', $sale->getTaxAmount(), \PDO::PARAM_STR);
        $statement->bindValue(':created_at', $sale->getCreatedAt(), \PDO::PARAM_STR);
        $statement->execute();
    }

    public function update(Sale $sale): void
    {
        $connection = $this->database->getConnection();
        $query = "UPDATE sales SET product_id = :product_id, quantity = :quantity, total = :total, tax_amount = :tax_amount WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':product_id', $sale->getProductId(), \PDO::PARAM_INT);
        $statement->bindValue(':quantity', $sale->getQuantity(), \PDO::PARAM_INT);
        $statement->bindValue(':total', $sale->getTotal(), \PDO::PARAM_STR);
        $statement->bindValue(':tax_amount', $sale->getTaxAmount(), \PDO::PARAM_STR);
        $statement->bindValue(':id', $sale->getId(), \PDO::PARAM_INT);
        $statement->execute();
    }

    public function delete(int $id): void
    {
        $connection = $this->database->getConnection();
        $query = "DELETE FROM sales WHERE id = :id";
        $statement = $connection->prepare($query);
        $statement->bindValue(':id', $id, \PDO::PARAM_INT);
        $statement->execute();
    }
}
