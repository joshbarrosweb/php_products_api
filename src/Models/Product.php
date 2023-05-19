<?php

namespace App\Models;

class Product
{
    private int $id;
    private string $name;
    private float $price;
    private int $quantity;
    private string $createdAt;

    public function __construct(int $id, string $name, float $price, int $quantity, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->createdAt = $createdAt;
    }

    // Getters and Setters

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
