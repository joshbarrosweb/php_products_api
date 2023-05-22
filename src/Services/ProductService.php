<?php

namespace App\Services;

use App\Interfaces\ProductRepositoryInterface;

use App\Models\Product;
use App\Dtos\ProductDTO;

class ProductService
{
    private ProductRepositoryInterface $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts(): array
    {
        return $this->productRepository->getAll();
    }

    public function getProductById(int $id): ?Product
    {
        return $this->productRepository->getById($id);
    }

    public function createProduct(ProductDTO $productDTO): void
    {
        $product = new Product(
            null,
            $productDTO->getName(),
            $productDTO->getPrice(),
            $productDTO->getQuantity(),
            $productDTO->getCreatedAt()
        );

        $this->productRepository->create($product);
    }


    public function updateProduct(int $id, ProductDTO $productDTO): void
    {
        $product = new Product(
            $id,
            $productDTO->getName(),
            $productDTO->getPrice(),
            $productDTO->getQuantity(),
            $productDTO->getCreatedAt()
        );

        $this->productRepository->update($product);
    }

    public function deleteProduct(int $id): void
    {
        $this->productRepository->delete($id);
    }
}
