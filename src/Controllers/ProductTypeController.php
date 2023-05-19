<?php

namespace App\Controllers;

use App\Services\ProductTypeService;
use App\DTO\ProductTypeDTO;
use App\Exceptions\ProductTypeNotFoundException;
use App\Validators\ProductTypeValidator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductTypeController
{
    private ProductTypeService $productTypeService;

    public function __construct(ProductTypeService $productTypeService)
    {
        $this->productTypeService = $productTypeService;
    }

    public function index(Request $request): JsonResponse
    {
        $productTypes = $this->productTypeService->getAllProductTypes();
        return new JsonResponse($productTypes);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $productType = $this->productTypeService->getProductTypeById($id);

        if (!$productType) {
            throw new ProductTypeNotFoundException($id);
        }

        return new JsonResponse($productType);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $errors = ProductTypeValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $productTypeDTO = new ProductTypeDTO(
            0,
            $data['name'],
            date('Y-m-d H:i:s')
        );

        $this->productTypeService->createProductType($productTypeDTO);

        return new JsonResponse(['message' => 'Product type created successfully'], JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $productType = $this->productTypeService->getProductTypeById($id);

        if (!$productType) {
            throw new ProductTypeNotFoundException($id);
        }

        $data = $request->toArray();
        $errors = ProductTypeValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
        }

        $productTypeDTO = new ProductTypeDTO(
            $id,
            $data['name'],
            $productType->getCreatedAt()
        );

        $this->productTypeService->updateProductType($id, $productTypeDTO);

        return new JsonResponse(['message' => 'Product type updated successfully'], JsonResponse::HTTP_OK);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $productType = $this->productTypeService->getProductTypeById($id);

        if (!$productType) {
            throw new ProductTypeNotFoundException($id);
        }

        $this->productTypeService->deleteProductType($id);

        return new JsonResponse(['message' => 'Product type deleted successfully'], JsonResponse::HTTP_OK);
    }
}
