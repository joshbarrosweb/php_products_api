<?php

namespace App\Interfaces;

use App\Models\Sale;

interface SaleRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?Sale;

    public function create(Sale $sale): void;

    public function update(Sale $sale): void;

    public function delete(int $id): void;
}
