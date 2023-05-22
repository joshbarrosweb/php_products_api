<?php

namespace App\Models;

class ProductType implements \JsonSerializable
{
    private ?int $id;
    private string $name;
    private string $createdAt;

    public function __construct( ?int $id = null, string $name, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
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

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function setCreatedAt(string $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function jsonSerialize()
    {
        return
        [
            'id'   => $this->getId(),
            'name' => $this->getName(),
            'created_at' => $this->getCreatedAt()
        ];
    }
}
