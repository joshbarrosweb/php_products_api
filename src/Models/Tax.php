<?php

namespace App\Models;

class Tax
{
    private ?int $id;
    private string $name;
    private float $rate;
    private string $createdAt;

    public function __construct(?int $id = null, string $name, float $rate, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->rate = $rate;
        $this->createdAt = $createdAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function setRate(float $rate): void
    {
        $this->rate = $rate;
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
