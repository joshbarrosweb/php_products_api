<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ProductTypeController;
use App\Services\ProductTypeService;
use App\DTO\ProductTypeDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductTypeControllerTest extends TestCase
{
    private ProductTypeController $productTypeController;
    private ProductTypeService $productTypeService;

    protected function setUp(): void
    {
        // Create a mock instance of the ProductTypeService
        $this->productTypeService = $this->createMock(ProductTypeService::class);

        // Create an instance of the ProductTypeController with the mock service
        $this->productTypeController = new ProductTypeController($this->productTypeService);
    }

    public function testIndex(): void
    {
        // Define a sample list of product types
        $productTypes = [
            ['id' => 1, 'name' => 'Type 1'],
            ['id' => 2, 'name' => 'Type 2'],
        ];

        // Set up the mock service to return the sample product types
        $this->productTypeService->expects($this->once())
            ->method('getAllProductTypes')
            ->willReturn($productTypes);

        // Create a mock Request object
        $request = Request::create('/product-types', 'GET');

        // Call the index method of the controller
        $response = $this->productTypeController->index($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected product types
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($productTypes, $responseData);
    }

    public function testShow(): void
    {
        // Define a sample product type
        $productType = ['id' => 1, 'name' => 'Type 1'];

        // Set up the mock service to return the sample product type
        $this->productTypeService->expects($this->once())
            ->method('getProductTypeById')
            ->with(1)
            ->willReturn($productType);

        // Create a mock Request object
        $request = Request::create('/product-types/1', 'GET');

        // Call the show method of the controller
        $response = $this->productTypeController->show($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected product type
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($productType, $responseData);
    }

    public function testCreate(): void
    {
        // Define the input data for creating a product type
        $requestData = [
            'name' => 'Type 1',
        ];

        // Set up the mock service to create the product type
        $this->productTypeService->expects($this->once())
            ->method('createProductType')
            ->with($this->isInstanceOf(ProductTypeDTO::class));

        // Create a mock Request object with the input data
        $request = Request::create('/product-types', 'POST', [], [], [], [], json_encode($requestData));

        // Call the create method of the controller
        $response = $this->productTypeController->create($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product type created successfully', $responseData['message']);
    }

    public function testUpdate(): void
    {
        // Define the input data for updating a product type
        $requestData = [
            'name' => 'Updated Type',
        ];

        // Set up the mock service to update the product type
        $this->productTypeService->expects($this->once())
            ->method('updateProductType')
            ->with(
                $this->equalTo(1),
                $this->isInstanceOf(ProductTypeDTO::class)
            );

        // Create a mock Request object with the input data
        $request = Request::create('/product-types/1', 'PUT', [], [], [], [], json_encode($requestData));

        // Call the update method of the controller
        $response = $this->productTypeController->update($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product type updated successfully', $responseData['message']);
    }

    public function testDelete(): void
    {
        // Set up the mock service to delete the product type
        $this->productTypeService->expects($this->once())
            ->method('deleteProductType')
            ->with(1);

        // Create a mock Request object
        $request = Request::create('/product-types/1', 'DELETE');

        // Call the delete method of the controller
        $response = $this->productTypeController->delete($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product type deleted successfully', $responseData['message']);
    }
}
