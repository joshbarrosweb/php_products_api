<?php

namespace App\Services;

use App\Interfaces\ProductTypeRepositoryInterface;

class ProductTypeService
{
    private ProductTypeRepositoryInterface $productTypeRepository;

    public function __construct(ProductTypeRepositoryInterface $productTypeRepository)
    {
        $this->productTypeRepository = $productTypeRepository;
    }

    public function getAllProductTypes(): array
    {
        return $this->productTypeRepository->getAll();
    }

    public function getProductTypeById(int $id): ?ProductType
    {
        return $this->productTypeRepository->getById($id);
    }

    public function createProductType(ProductTypeDTO $productTypeDTO): void
    {
        $productType = new ProductType(
            $productTypeDTO->getId(),
            $productTypeDTO->getName(),
            $productTypeDTO->getCreatedAt()
        );

        $this->productTypeRepository->create($productType);
    }

    public function updateProductType(int $id, ProductTypeDTO $productTypeDTO): void
    {
        $productType = new ProductType(
            $id,
            $productTypeDTO->getName(),
            $productTypeDTO->getCreatedAt()
        );

        $this->productTypeRepository->update($productType);
    }

    public function deleteProductType(int $id): void
    {
        $this->productTypeRepository->delete($id);
    }
}
