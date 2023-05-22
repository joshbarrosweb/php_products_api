<?php

namespace App\Dtos;

class SaleDTO
{
    private ?int $id;
    private int $productId;
    private int $quantity;
    private float $total;
    private float $taxAmount;
    private string $createdAt;

    public function __construct(?int $id = null, int $productId, int $quantity, float $total, float $taxAmount, string $createdAt)
    {
        $this->id = $id;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->total = $total;
        $this->taxAmount = $taxAmount;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getTotal(): float
    {
        return $this->total;
    }

    public function getTaxAmount(): float
    {
        return $this->taxAmount;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
