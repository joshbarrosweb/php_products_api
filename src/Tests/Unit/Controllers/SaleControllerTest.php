<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\SaleController;
use App\Services\SaleService;
use App\DTO\SaleDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class SaleControllerTest extends TestCase
{
    private SaleController $saleController;
    private SaleService $saleService;

    protected function setUp(): void
    {
        // Create a mock instance of the SaleService
        $this->saleService = $this->createMock(SaleService::class);

        // Create an instance of the SaleController with the mock service
        $this->saleController = new SaleController($this->saleService);
    }

    public function testIndex(): void
    {
        // Define a sample list of sales
        $sales = [
            ['id' => 1, 'product_id' => 1, 'quantity' => 2, 'total' => 20.0, 'tax_amount' => 2.0],
            ['id' => 2, 'product_id' => 2, 'quantity' => 3, 'total' => 30.0, 'tax_amount' => 3.0],
        ];

        // Set up the mock service to return the sample sales
        $this->saleService->expects($this->once())
            ->method('getAllSales')
            ->willReturn($sales);

        // Create a mock Request object
        $request = Request::create('/sales', 'GET');

        // Call the index method of the controller
        $response = $this->saleController->index($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected sales
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($sales, $responseData);
    }

    public function testShow(): void
    {
        // Define a sample sale
        $sale = ['id' => 1, 'product_id' => 1, 'quantity' => 2, 'total' => 20.0, 'tax_amount' => 2.0];

        // Set up the mock service to return the sample sale
        $this->saleService->expects($this->once())
            ->method('getSaleById')
            ->with(1)
            ->willReturn($sale);

        // Create a mock Request object
        $request = Request::create('/sales/1', 'GET');

        // Call the show method of the controller
        $response = $this->saleController->show($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected sale
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($sale, $responseData);
    }

    public function testCreate(): void
    {
        // Define the input data for creating a sale
        $requestData = [
            'product_id' => 1,
            'quantity' => 2,
            'total' => 20.0,
            'tax_amount' => 2.0,
        ];

        // Set up the mock service to create the sale
        $this->saleService->expects($this->once())
            ->method('createSale')
            ->with($this->isInstanceOf(SaleDTO::class));

        // Create a mock Request object with the input data
        $request = Request::create('/sales', 'POST', [], [], [], [], json_encode($requestData));

        // Call the create method of the controller
        $response = $this->saleController->create($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Sale created successfully', $responseData['message']);
    }

    public function testUpdate(): void
    {
        // Define the input data for updating a sale
        $requestData = [
            'product_id' => 1,
            'quantity' => 3,
            'total' => 30.0,
            'tax_amount' => 3.0,
        ];

        // Set up the mock service to update the sale
        $this->saleService->expects($this->once())
            ->method('updateSale')
            ->with(
                $this->equalTo(1),
                $this->isInstanceOf(SaleDTO::class)
            );

        // Create a mock Request object with the input data
        $request = Request::create('/sales/1', 'PUT', [], [], [], [], json_encode($requestData));

        // Call the update method of the controller
        $response = $this->saleController->update($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Sale updated successfully', $responseData['message']);
    }

    public function testDelete(): void
    {
        // Set up the mock service to delete the sale
        $this->saleService->expects($this->once())
            ->method('deleteSale')
            ->with(1);

        // Create a mock Request object
        $request = Request::create('/sales/1', 'DELETE');

        // Call the delete method of the controller
        $response = $this->saleController->delete($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Sale deleted successfully', $responseData['message']);
    }
}
