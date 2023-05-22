<?php

namespace App\Tests\Controllers;

use App\Controllers\SaleController;
use App\Services\SaleService;
use App\Dtos\SaleDTO;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class SaleControllerTest extends TestCase
{
    private $saleService;
    private $saleController;

    protected function setUp(): void
    {
        $this->saleService = $this->createMock(SaleService::class);
        $this->saleController = new SaleController($this->saleService);
    }

    public function testIndex(): void
    {
        // Arrange
        $salesData = [
            new SaleDTO(1, 1, 1, 100.0, 10.0, '2023-05-21 00:00:00'),
            new SaleDTO(2, 2, 2, 200.0, 20.0, '2023-05-21 00:00:00'),
        ];

        $this->saleService
            ->expects($this->once())
            ->method('getAllSales')
            ->willReturn($salesData);

        // Act
        $response = $this->saleController->index(new Request());

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($salesData, $response->getData());
    }

    public function testShow(): void
    {
        // Arrange
        $saleData = new SaleDTO(1, 1, 1, 100.0, 10.0, '2023-05-21 00:00:00');

        $this->saleService
            ->expects($this->once())
            ->method('getSaleById')
            ->willReturn($saleData);

        // Act
        $response = $this->saleController->show(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($saleData, $response->getData());
    }

    public function testShowNotFound(): void
    {
        // Arrange
        $this->saleService
            ->expects($this->once())
            ->method('getSaleById')
            ->willReturn(null);

        // Act
        $response = $this->saleController->show(new Request(), 1);

        // Assert
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Sale not found', 'message' => 'Sale with id 1 not found'], $response->getData());
    }

    public function testCreate(): void
    {
        // Arrange
        $saleData = [
            'product_id' => 1,
            'quantity' => 1,
            'total' => 100.0,
            'tax_amount' => 10.0
        ];

        $request = new Request([], [], [], [], [], [], json_encode($saleData));

        $this->saleService
            ->expects($this->once())
            ->method('createSale');

        // Act
        $response = $this->saleController->create($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Sale created successfully'], $response->getData());
    }

    public function testUpdate(): void
    {
        // Arrange
        $saleData = [
            'product_id' => 1,
            'quantity' => 2,
            'total' => 200.0,
            'tax_amount' => 20.0
        ];

        $sale = new SaleDTO(
            1,
            $saleData['product_id'],
            $saleData['quantity'],
            $saleData['total'],
            $saleData['tax_amount'],
            '2023-05-21 00:00:00'
        );

        $request = new Request([], [], [], [], [], [], json_encode($saleData));

        $this->saleService
            ->expects($this->once())
            ->method('getSaleById')
            ->willReturn($sale);

        $this->saleService
            ->expects($this->once())
            ->method('updateSale');

        // Act
        $response = $this->saleController->update($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Sale updated successfully'], $response->getData());
    }

    public function testDelete(): void
    {
        // Arrange
        $saleData = [
            'product_id' => 1,
            'quantity' => 1,
            'total' => 100.0,
            'tax_amount' => 10.0
        ];

        $sale = new SaleDTO(
            1,
            $saleData['product_id'],
            $saleData['quantity'],
            $saleData['total'],
            $saleData['tax_amount'],
            '2023-05-21 00:00:00'
        );

        $this->saleService
            ->expects($this->once())
            ->method('getSaleById')
            ->willReturn($sale);

        $this->saleService
            ->expects($this->once())
            ->method('deleteSale');

        // Act
        $response = $this->saleController->delete(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Sale deleted successfully'], $response->getData());
    }

}
