<?php

namespace App\Interfaces;

use App\Models\ProductType;

interface ProductTypeRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?ProductType;

    public function create(ProductType $productType): void;

    public function update(ProductType $productType): void;

    public function delete(int $id): void;
}
