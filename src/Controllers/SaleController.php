<?php

namespace App\Controllers;

use App\Services\SaleService;
use App\Dtos\SaleDTO;
use App\Exceptions\SaleNotFoundException;
use App\Validators\SaleValidator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SaleController
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $sales = $this->saleService->getAllSales();
            return new JsonResponse($sales);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                throw new SaleNotFoundException($id);
            }

            return new JsonResponse($sale);
        } catch (SaleNotFoundException $e) {
            return new JsonResponse(['error' => 'Sale not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->toArray();
            $errors = SaleValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $saleDTO = new SaleDTO(
              null,
              $data['product_id'],
              $data['quantity'],
              $data['total'],
              isset($data['tax_amount']) ? (float) $data['tax_amount'] : null,
              date('Y-m-d H:i:s')
            );

            $this->saleService->createSale($saleDTO);

            return new JsonResponse(['message' => 'Sale created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                throw new SaleNotFoundException($id);
            }

            $data = $request->toArray();
            $errors = SaleValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $saleDTO = new SaleDTO(
              $id,
              $data['product_id'],
              $data['quantity'],
              $data['total'],
              isset($data['tax_amount']) ? (float) $data['tax_amount'] : null,
              date('Y-m-d H:i:s')
            );


            $this->saleService->updateSale($id, $saleDTO);

            return new JsonResponse(['message' => 'Sale updated successfully'], Response::HTTP_OK);
        } catch (SaleNotFoundException $e) {
            return new JsonResponse(['error' => 'Sale not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $sale = $this->saleService->getSaleById($id);

            if (!$sale) {
                throw new SaleNotFoundException($id);
            }

            $this->saleService->deleteSale($id);

            return new JsonResponse(['message' => 'Sale deleted successfully'], Response::HTTP_OK);
        } catch (SaleNotFoundException $e) {
            return new JsonResponse(['error' => 'Sale not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
