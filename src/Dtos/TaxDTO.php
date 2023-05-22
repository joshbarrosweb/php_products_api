<?php

namespace App\Dtos;

class TaxDTO
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

    public function getName(): string
    {
        return $this->name;
    }

    public function getRate(): float
    {
        return $this->rate;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}
