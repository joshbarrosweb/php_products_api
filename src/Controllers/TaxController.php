<?php

namespace App\Controllers;

use App\Services\TaxService;
use App\DTO\TaxDTO;
use App\Exceptions\TaxNotFoundException;
use App\Validators\TaxValidator;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxController
{
    private TaxService $taxService;

    public function __construct(TaxService $taxService)
    {
        $this->taxService = $taxService;
    }

    public function index(Request $request): JsonResponse
    {
        $taxes = $this->taxService->getAllTaxes();
        return new JsonResponse($taxes, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tax = $this->taxService->getTaxById($id);

        if (!$tax) {
            throw new TaxNotFoundException($id);
        }

        return new JsonResponse($tax, Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function create(Request $request): JsonResponse
    {
        $data = $request->toArray();
        $errors = TaxValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $taxDTO = new TaxDTO(
            0,
            $data['name'],
            $data['rate'],
            date('Y-m-d H:i:s')
        );

        $this->taxService->createTax($taxDTO);

        return new JsonResponse(['message' => 'Tax created successfully'], Response::HTTP_CREATED, ['Content-Type' => 'application/json']);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $tax = $this->taxService->getTaxById($id);

        if (!$tax) {
            throw new TaxNotFoundException($id);
        }

        $data = $request->toArray();
        $errors = TaxValidator::validate($data);

        if (count($errors) > 0) {
            return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        $taxDTO = new TaxDTO(
            $id,
            $data['name'],
            $data['rate'],
            $tax->getCreatedAt()
        );

        $this->taxService->updateTax($id, $taxDTO);

        return new JsonResponse(['message' => 'Tax updated successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        $tax = $this->taxService->getTaxById($id);

        if (!$tax) {
            throw new TaxNotFoundException($id);
        }

        $this->taxService->deleteTax($id);

        return new JsonResponse(['message' => 'Tax deleted successfully'], Response::HTTP_OK, ['Content-Type' => 'application/json']);
    }
}
