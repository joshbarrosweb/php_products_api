<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Dtos\ProductDTO;
use App\Exceptions\ProductNotFoundException;
use App\Validators\ProductValidator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController
{
    private ProductService $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $products = $this->productService->getAllProducts();
            return new JsonResponse($products);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                throw new ProductNotFoundException($id);
            }

            return new JsonResponse($product);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => 'Product not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->toArray();
            $errors = ProductValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
            }

            $productDTO = new ProductDTO(
                null,
                $data['name'],
                $data['price'],
                $data['quantity'],
                date('Y-m-d H:i:s')
            );

            $this->productService->createProduct($productDTO);

            return new JsonResponse(['message' => 'Product created successfully'], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                throw new ProductNotFoundException($id);
            }

            $data = $request->toArray();
            $errors = ProductValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
            }

            $productDTO = new ProductDTO(
                $id,
                $data['name'],
                $data['price'],
                $data['quantity'],
                $product->getCreatedAt()
            );

            $this->productService->updateProduct($id, $productDTO);

            return new JsonResponse(['message' => 'Product updated successfully'], JsonResponse::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => 'Product not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $product = $this->productService->getProductById($id);

            if (!$product) {
                throw new ProductNotFoundException($id);
            }

            $this->productService->deleteProduct($id);

            return new JsonResponse(['message' => 'Product deleted successfully'], JsonResponse::HTTP_OK);
        } catch (ProductNotFoundException $e) {
            return new JsonResponse(['error' => 'Product not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
