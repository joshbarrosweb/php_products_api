<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?Product;

    public function create(Product $product): void;

    public function update(Product $product): void;

    public function delete(int $id): void;
}
