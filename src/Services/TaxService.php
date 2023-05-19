<?php

namespace App\Services;

use App\Interfaces\TaxRepositoryInterface;

class TaxService
{
    private TaxRepositoryInterface $taxRepository;

    public function __construct(TaxRepositoryInterface $taxRepository)
    {
        $this->taxRepository = $taxRepository;
    }

    public function getAllTaxes(): array
    {
        return $this->taxRepository->getAll();
    }

    public function getTaxById(int $id): ?Tax
    {
        return $this->taxRepository->getById($id);
    }

    public function createTax(TaxDTO $taxDTO): void
    {
        $tax = new Tax(
            $taxDTO->getId(),
            $taxDTO->getName(),
            $taxDTO->getRate(),
            $taxDTO->getCreatedAt()
        );

        $this->taxRepository->create($tax);
    }

    public function updateTax(int $id, TaxDTO $taxDTO): void
    {
        $tax = new Tax(
            $id,
            $taxDTO->getName(),
            $taxDTO->getRate(),
            $taxDTO->getCreatedAt()
        );

        $this->taxRepository->update($tax);
    }

    public function deleteTax(int $id): void
    {
        $this->taxRepository->delete($id);
    }
}
