<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\DTO\ProductDTO;
use App\Exceptions\ProductNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        $products = $this->productService->getAllProducts();
        return new JsonResponse(['products' => $products], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        return new JsonResponse($product, JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $errors = ProductValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $productDTO = new ProductDTO(
            0,
            $data['name'],
            $data['price'],
            $data['quantity'],
            date('Y-m-d H:i:s')
        );

        $this->productService->createProduct($productDTO);

        return new JsonResponse(['message' => 'Product created successfully'], JsonResponse::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        $data = $request->toArray();
        $errors = ProductValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $productDTO = new ProductDTO(
            $id,
            $data['name'],
            $data['price'],
            $data['quantity'],
            $product->getCreatedAt()
        );

        $this->productService->updateProduct($id, $productDTO);

        return new JsonResponse(['message' => 'Product updated successfully'], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            throw new ProductNotFoundException($id);
        }

        $this->productService->deleteProduct($id);

        return new JsonResponse(['message' => 'Product deleted successfully'], JsonResponse::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
