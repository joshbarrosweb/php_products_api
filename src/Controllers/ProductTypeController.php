<?php

namespace App\Controllers;

use App\Services\ProductTypeService;
use App\Dtos\ProductTypeDTO;
use App\Exceptions\ProductTypeNotFoundException;
use App\Validators\ProductTypeValidator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductTypeController
{
    private ProductTypeService $productTypeService;

    public function __construct(ProductTypeService $productTypeService)
    {
        $this->productTypeService = $productTypeService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $productTypes = $this->productTypeService->getAllProductTypes();
            return new JsonResponse($productTypes);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $productType = $this->productTypeService->getProductTypeById($id);

            if (!$productType) {
                throw new ProductTypeNotFoundException($id);
            }

            return new JsonResponse($productType);
        } catch (ProductTypeNotFoundException $e) {
            return new JsonResponse(['error' => 'Product type not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->toArray();
            $errors = ProductTypeValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], JsonResponse::HTTP_BAD_REQUEST);
            }

            $productTypeDTO = new ProductTypeDTO(
                null,
                $data['name'],
                date('Y-m-d H:i:s')
            );

            $this->productTypeService->createProductType($productTypeDTO);

            return new JsonResponse(['message' => 'Product type created successfully'], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
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
        } catch (ProductTypeNotFoundException $e) {
            return new JsonResponse(['error' => 'Product type not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $productType = $this->productTypeService->getProductTypeById($id);

            if (!$productType) {
                throw new ProductTypeNotFoundException($id);
            }

            $this->productTypeService->deleteProductType($id);

            return new JsonResponse(['message' => 'Product type deleted successfully'], JsonResponse::HTTP_OK);
        } catch (ProductTypeNotFoundException $e) {
            return new JsonResponse(['error' => 'Product type not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
