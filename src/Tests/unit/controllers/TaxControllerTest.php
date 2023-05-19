<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\TaxController;
use App\Services\TaxService;
use App\DTO\TaxDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TaxControllerTest extends TestCase
{
    private TaxController $taxController;
    private TaxService $taxService;

    protected function setUp(): void
    {
        // Create a mock instance of the TaxService
        $this->taxService = $this->createMock(TaxService::class);

        // Create an instance of the TaxController with the mock service
        $this->taxController = new TaxController($this->taxService);
    }

    public function testIndex(): void
    {
        // Define a sample list of taxes
        $taxes = [
            ['id' => 1, 'name' => 'Tax 1', 'rate' => 0.1],
            ['id' => 2, 'name' => 'Tax 2', 'rate' => 0.2],
        ];

        // Set up the mock service to return the sample taxes
        $this->taxService->expects($this->once())
            ->method('getAllTaxes')
            ->willReturn($taxes);

        // Create a mock Request object
        $request = Request::create('/taxes', 'GET');

        // Call the index method of the controller
        $response = $this->taxController->index($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected taxes
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($taxes, $responseData);
    }

    public function testShow(): void
    {
        // Define a sample tax
        $tax = ['id' => 1, 'name' => 'Tax 1', 'rate' => 0.1];

        // Set up the mock service to return the sample tax
        $this->taxService->expects($this->once())
            ->method('getTaxById')
            ->with(1)
            ->willReturn($tax);

        // Create a mock Request object
        $request = Request::create('/taxes/1', 'GET');

        // Call the show method of the controller
        $response = $this->taxController->show($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected tax
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($tax, $responseData);
    }

    public function testCreate(): void
    {
        // Define the input data for creating a tax
        $requestData = [
            'name' => 'Tax 1',
            'rate' => 0.1,
        ];

        // Set up the mock service to create the tax
        $this->taxService->expects($this->once())
            ->method('createTax')
            ->with($this->isInstanceOf(TaxDTO::class));

        // Create a mock Request object with the input data
        $request = Request::create('/taxes', 'POST', [], [], [], [], json_encode($requestData));

        // Call the create method of the controller
        $response = $this->taxController->create($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Tax created successfully', $responseData['message']);
    }

    public function testUpdate(): void
    {
        // Define the input data for updating a tax
        $requestData = [
            'name' => 'Updated Tax',
            'rate' => 0.2,
        ];

        // Set up the mock service to update the tax
        $this->taxService->expects($this->once())
            ->method('updateTax')
            ->with(
                $this->equalTo(1),
                $this->isInstanceOf(TaxDTO::class)
            );

        // Create a mock Request object with the input data
        $request = Request::create('/taxes/1', 'PUT', [], [], [], [], json_encode($requestData));

        // Call the update method of the controller
        $response = $this->taxController->update($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Tax updated successfully', $responseData['message']);
    }

    public function testDelete(): void
    {
        // Set up the mock service to delete the tax
        $this->taxService->expects($this->once())
            ->method('deleteTax')
            ->with(1);

        // Create a mock Request object
        $request = Request::create('/taxes/1', 'DELETE');

        // Call the delete method of the controller
        $response = $this->taxController->delete($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Tax deleted successfully', $responseData['message']);
    }
}
