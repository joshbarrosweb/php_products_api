<?php

namespace App\Controllers;

use App\Services\TaxService;
use App\Dtos\TaxDTO;
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
        try {
            $taxes = $this->taxService->getAllTaxes();
            return new JsonResponse($taxes);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $tax = $this->taxService->getTaxById($id);

            if (!$tax) {
                throw new TaxNotFoundException($id);
            }

            return new JsonResponse($tax);
        } catch (TaxNotFoundException $e) {
            return new JsonResponse(['error' => 'Tax not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->toArray();
            $errors = TaxValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $taxDTO = new TaxDTO(
                null,
                $data['name'],
                $data['rate'],
                date('Y-m-d H:i:s')
            );

            $this->taxService->createTax($taxDTO);

            return new JsonResponse(['message' => 'Tax created successfully'], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $tax = $this->taxService->getTaxById($id);

            if (!$tax) {
                throw new TaxNotFoundException($id);
            }

            $data = $request->toArray();
            $errors = TaxValidator::validate($data);

            if (count($errors) > 0) {
                return new JsonResponse(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }

            $taxDTO = new TaxDTO(
                $id,
                $data['name'],
                (float) $data['rate'], // Explicitly cast rate to float
                $tax->getCreatedAt()
            );

            $this->taxService->updateTax($id, $taxDTO);

            return new JsonResponse(['message' => 'Tax updated successfully'], Response::HTTP_OK);
        } catch (TaxNotFoundException $e) {
            return new JsonResponse(['error' => 'Tax not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete(Request $request, int $id): JsonResponse
    {
        try {
            $tax = $this->taxService->getTaxById($id);

            if (!$tax) {
                throw new TaxNotFoundException($id);
            }

            $this->taxService->deleteTax($id);

            return new JsonResponse(['message' => 'Tax deleted successfully'], Response::HTTP_OK);
        } catch (TaxNotFoundException $e) {
            return new JsonResponse(['error' => 'Tax not found', 'message' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Internal server error', 'message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
