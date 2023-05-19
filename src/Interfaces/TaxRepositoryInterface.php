<?php

namespace App\Interfaces;

use App\Models\Tax;

interface TaxRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?Tax;

    public function create(Tax $tax): void;

    public function update(Tax $tax): void;

    public function delete(int $id): void;
}
