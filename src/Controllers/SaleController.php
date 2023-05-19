<?php

namespace App\Controllers;

use App\Services\SaleService;
use App\DTO\SaleDTO;
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
        $sales = $this->saleService->getAllSales();
        return new JsonResponse($sales, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleById($id);

        if (!$sale) {
            throw new SaleNotFoundException($id);
        }

        return new JsonResponse($sale, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $errors = SaleValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $saleDTO = new SaleDTO(
            0,
            $data['product_id'],
            $data['quantity'],
            $data['total'],
            $data['tax_amount'],
            date('Y-m-d H:i:s')
        );

        $this->saleService->createSale($saleDTO);

        return new JsonResponse(['message' => 'Sale created successfully'], Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleById($id);

        if (!$sale) {
            throw new SaleNotFoundException($id);
        }

        $data = $request->toArray();
        $errors = SaleValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $saleDTO = new SaleDTO(
            $id,
            $data['product_id'],
            $data['quantity'],
            $data['total'],
            $data['tax_amount'],
            $sale->getCreatedAt()
        );

        $this->saleService->updateSale($id, $saleDTO);

        return new JsonResponse(['message' => 'Sale updated successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $sale = $this->saleService->getSaleById($id);

        if (!$sale) {
            throw new SaleNotFoundException($id);
        }

        $this->saleService->deleteSale($id);

        return new JsonResponse(['message' => 'Sale deleted successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
