<?php

namespace App\Tests\Controllers;

use App\Controllers\ProductTypeController;
use App\Services\ProductTypeService;
use App\Dtos\ProductTypeDTO;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class ProductTypeControllerTest extends TestCase
{
    private $productTypeService;
    private $productTypeController;

    protected function setUp(): void
    {
        $this->productTypeService = $this->createMock(ProductTypeService::class);
        $this->productTypeController = new ProductTypeController($this->productTypeService);
    }

    public function testIndex(): void
    {
        // Arrange
        $productTypesData = [
            new ProductTypeDTO(1, 'Product Type 1', '2023-05-21 00:00:00'),
            new ProductTypeDTO(2, 'Product Type 2', '2023-05-21 00:00:00'),
        ];

        $this->productTypeService
            ->expects($this->once())
            ->method('getAllProductTypes')
            ->willReturn($productTypesData);

        // Act
        $response = $this->productTypeController->index(new Request());

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($productTypesData, $response->getData());
    }

    public function testShow(): void
    {
        // Arrange
        $productTypeData = new ProductTypeDTO(1, 'Product Type 1', '2023-05-21 00:00:00');

        $this->productTypeService
            ->expects($this->once())
            ->method('getProductTypeById')
            ->willReturn($productTypeData);

        // Act
        $response = $this->productTypeController->show(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($productTypeData, $response->getData());
    }

    public function testShowNotFound(): void
    {
        // Arrange
        $this->productTypeService
            ->expects($this->once())
            ->method('getProductTypeById')
            ->willReturn(null);

        // Act
        $response = $this->productTypeController->show(new Request(), 1);

        // Assert
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Product type not found', 'message' => 'Product Type with id 1 not found'], $response->getData());
    }


    public function testCreate(): void
    {
        // Arrange
        $productTypeData = [
            'name' => 'Test product type'
        ];

        $request = new Request([], [], [], [], [], [], json_encode($productTypeData));

        $this->productTypeService
            ->expects($this->once())
            ->method('createProductType');

        // Act
        $response = $this->productTypeController->create($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product type created successfully'], $response->getData());
    }

    public function testUpdate(): void
    {
        // Arrange
        $productTypeData = [
            'name' => 'Updated product type',
        ];

        $productType = new ProductTypeDTO(
            1,
            $productTypeData['name'],
            '2023-05-21 00:00:00'
        );

        $request = new Request([], [], [], [], [], [], json_encode($productTypeData));

        $this->productTypeService
            ->expects($this->once())
            ->method('getProductTypeById')
            ->willReturn($productType);

        $this->productTypeService
            ->expects($this->once())
            ->method('updateProductType');

        // Act
        $response = $this->productTypeController->update($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product type updated successfully'], $response->getData());
    }

    public function testDelete(): void
    {
        // Arrange
        $productTypeData = [
            'name' => 'Product type to delete',
        ];

        $productType = new ProductTypeDTO(
            1,
            $productTypeData['name'],
            '2023-05-21 00:00:00'
        );

        $this->productTypeService
            ->expects($this->once())
            ->method('getProductTypeById')
            ->willReturn($productType);

        $this->productTypeService
            ->expects($this->once())
            ->method('deleteProductType');

        // Act
        $response = $this->productTypeController->delete(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Product type deleted successfully'], $response->getData());
    }

}
