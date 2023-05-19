<?php

namespace App\Services;

use App\Interfaces\SaleRepositoryInterface;

class SaleService
{
    private SaleRepositoryInterface $saleRepository;

    public function __construct(SaleRepositoryInterface $saleRepository)
    {
        $this->saleRepository = $saleRepository;
    }

    public function getAllSales(): array
    {
        return $this->saleRepository->getAll();
    }

    public function getSaleById(int $id): ?Sale
    {
        return $this->saleRepository->getById($id);
    }

    public function createSale(SaleDTO $saleDTO): void
    {
        $sale = new Sale(
            $saleDTO->getId(),
            $saleDTO->getProductId(),
            $saleDTO->getQuantity(),
            $saleDTO->getTotal(),
            $saleDTO->getTaxAmount(),
            $saleDTO->getCreatedAt()
        );

        $this->saleRepository->create($sale);
    }

    public function updateSale(int $id, SaleDTO $saleDTO): void
    {
        $sale = new Sale(
            $id,
            $saleDTO->getProductId(),
            $saleDTO->getQuantity(),
            $saleDTO->getTotal(),
            $saleDTO->getTaxAmount(),
            $saleDTO->getCreatedAt()
        );

        $this->saleRepository->update($sale);
    }

    public function deleteSale(int $id): void
    {
        $this->saleRepository->delete($id);
    }
}
