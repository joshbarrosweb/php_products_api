<?php

namespace App\Dtos;

class ProductDTO
{
    private ?int $id;
    private string $name;
    private float $price;
    private int $quantity;
    private string $createdAt;

    public function __construct(?int $id = null, string $name, float $price, int $quantity, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
