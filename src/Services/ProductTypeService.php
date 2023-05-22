<?php

namespace App\Services;

use App\Interfaces\ProductTypeRepositoryInterface;

use App\Models\ProductType;
use App\DTOs\ProductTypeDTO;

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
            null,
            $productTypeDTO->getName(),
            $productTypeDTO->getCreatedAt()
        );

        $this->productTypeRepository->create($productType);
    }

    public function updateProductType(int $id, ProductTypeDTO $productTypeDTO): void
    {
        $existingProductType = $this->getProductTypeById($id);

        if ($existingProductType) {
            $existingProductType->setName($productTypeDTO->getName());
            $existingProductType->setCreatedAt($productTypeDTO->getCreatedAt());

            $this->productTypeRepository->update($existingProductType);
        }
    }


    public function deleteProductType(int $id): void
    {
        $this->productTypeRepository->delete($id);
    }
}
