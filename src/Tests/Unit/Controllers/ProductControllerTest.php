<?php

namespace Tests;

use App\Controllers\ProductController;
use App\Services\ProductService;
use App\Exceptions\ProductNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class ProductControllerTest extends TestCase
{
    private $productService;
    private $productController;

    protected function setUp(): void
    {
        $this->productService = $this->createMock(ProductService::class);
        $this->productController = new ProductController($this->productService);
    }

    public function testIndex(): void
    {
        // Arrange
        $productData = [
          'name' => 'Test product',
          'price' => 123.45,
          'quantity' => 10,
          'createdAt' => '2023-05-21 00:00:00'
      ];

        $this->productService
            ->expects($this->once())
            ->method('getAllProducts')
            ->willReturn($products);

        $request = new Request();

        // Act
        $response = $this->productController->index($request);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($products, $response->getData());
    }

    public function testShow(): void
    {
        // Arrange
        $productData = [
          'name' => 'Test product',
          'price' => 123.45,
          'quantity' => 10,
          'createdAt' => '2023-05-21 00:00:00'
      ];

        $this->productService
            ->expects($this->once())
            ->method('getProductById')
            ->willReturn($product);

        $request = new Request();

        // Act
        $response = $this->productController->show($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($product, $response->getData());
    }

    public function testShowProductNotFoundException(): void
    {
        // Arrange
        $this->productService
            ->expects($this->once())
            ->method('getProductById')
            ->willReturn(null);

        $request = new Request();

        // Act
        $response = $this->productController->show($request, 1);

        // Assert
        $this->assertEquals(404, $response->getStatusCode());
    }

    public function testCreate(): void
    {
        // Arrange
        $productData = [
            'name' => 'Test product',
            'price' => 123.45,
            'quantity' => 10
        ];

        $request = new Request([], [], [], [], [], [], json_encode($productData));

        $this->productService
            ->expects($this->once())
            ->method('createProduct');

        // Act
        $response = $this->productController->create($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product created successfully'], $response->getData());
    }

    public function testUpdate(): void
    {
        // Arrange
        $productData = [
            'name' => 'Test product',
            'price' => 123.45,
            'quantity' => 10,
            'createdAt' => '2023-05-21 00:00:00'
        ];

        $product = new ProductDTO(
            1,
            $productData['name'],
            $productData['price'],
            $productData['quantity'],
            $productData['createdAt']
        );

        $request = new Request([], [], [], [], [], [], json_encode($productData));

        $this->productService
            ->expects($this->once())
            ->method('getProductById')
            ->willReturn($product);

        $this->productService
            ->expects($this->once())
            ->method('updateProduct');

        // Act
        $response = $this->productController->update($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product updated successfully'], $response->getData());
    }

    public function testDelete(): void
    {
        // Arrange
        $productData = [
            'name' => 'Test product',
            'price' => 123.45,
            'quantity' => 10,
            'createdAt' => '2023-05-21 00:00:00'
        ];

        $product = new ProductDTO(
            1,
            $productData['name'],
            $productData['price'],
            $productData['quantity'],
            $productData['createdAt']
        );

        $this->productService
            ->expects($this->once())
            ->method('getProductById')
            ->willReturn($product);

        $this->productService
            ->expects($this->once())
            ->method('deleteProduct');

        // Act
        $response = $this->productController->delete(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product deleted successfully'], $response->getData());
    }
}
