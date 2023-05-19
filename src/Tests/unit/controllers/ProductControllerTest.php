<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\ProductController;
use App\Services\ProductService;
use App\DTO\ProductDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class ProductControllerTest extends TestCase
{
    private ProductController $productController;
    private ProductService $productService;

    protected function setUp(): void
    {
        // Create a mock instance of the ProductService
        $this->productService = $this->createMock(ProductService::class);

        // Create an instance of the ProductController with the mock service
        $this->productController = new ProductController($this->productService);
    }

    public function testIndex(): void
    {
        // Define a sample list of products
        $products = [
            ['id' => 1, 'name' => 'Product 1', 'price' => 10.99],
            ['id' => 2, 'name' => 'Product 2', 'price' => 19.99],
        ];

        // Set up the mock service to return the sample products
        $this->productService->expects($this->once())
            ->method('getAllProducts')
            ->willReturn($products);

        // Create a mock Request object
        $request = Request::create('/products', 'GET');

        // Call the index method of the controller
        $response = $this->productController->index($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected products
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($products, $responseData);
    }

    public function testShow(): void
    {
        // Define a sample product
        $product = ['id' => 1, 'name' => 'Product 1', 'price' => 10.99];

        // Set up the mock service to return the sample product
        $this->productService->expects($this->once())
            ->method('getProductById')
            ->with(1)
            ->willReturn($product);

        // Create a mock Request object
        $request = Request::create('/products/1', 'GET');

        // Call the show method of the controller
        $response = $this->productController->show($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert that the response contains the expected product
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals($product, $responseData);
    }

    public function testCreate(): void
    {
        // Define the input data for creating a product
        $requestData = [
            'name' => 'Product 1',
            'price' => 10.99,
            'quantity' => 5,
        ];

        // Set up the mock service to create the product
        $this->productService->expects($this->once())
            ->method('createProduct')
            ->with($this->isInstanceOf(ProductDTO::class));

        // Create a mock Request object with the input data
        $request = Request::create('/products', 'POST', [], [], [], [], json_encode($requestData));

        // Call the create method of the controller
        $response = $this->productController->create($request);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_CREATED, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product created successfully', $responseData['message']);
    }

    public function testUpdate(): void
    {
        // Define the input data for updating a product
        $requestData = [
            'name' => 'Updated Product',
            'price' => 19.99,
            'quantity' => 10,
        ];

        // Set up the mock service to update the product
        $this->productService->expects($this->once())
            ->method('updateProduct')
            ->with(
                $this->equalTo(1),
                $this->isInstanceOf(ProductDTO::class)
            );

        // Create a mock Request object with the input data
        $request = Request::create('/products/1', 'PUT', [], [], [], [], json_encode($requestData));

        // Call the update method of the controller
        $response = $this->productController->update($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product updated successfully', $responseData['message']);
    }

    public function testDelete(): void
    {
        // Set up the mock service to delete the product
        $this->productService->expects($this->once())
            ->method('deleteProduct')
            ->with(1);

        // Create a mock Request object
        $request = Request::create('/products/1', 'DELETE');

        // Call the delete method of the controller
        $response = $this->productController->delete($request, 1);

        // Assert that the response is a JsonResponse
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert the response status code
        $this->assertEquals(JsonResponse::HTTP_OK, $response->getStatusCode());

        // Assert the response message
        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('Product deleted successfully', $responseData['message']);
    }
}
