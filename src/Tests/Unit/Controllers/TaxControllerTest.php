<?php

namespace App\Tests\Controllers;

use App\Controllers\TaxController;
use App\Services\TaxService;
use App\Dtos\TaxDTO;
use Symfony\Component\HttpFoundation\Request;
use PHPUnit\Framework\TestCase;

class TaxControllerTest extends TestCase
{
    private $taxService;
    private $taxController;

    protected function setUp(): void
    {
        $this->taxService = $this->createMock(TaxService::class);
        $this->taxController = new TaxController($this->taxService);
    }

    public function testIndex(): void
    {
        // Arrange
        $taxesData = [
            new TaxDTO(1, 'VAT', 20.0, '2023-05-21 00:00:00'),
            new TaxDTO(2, 'GST', 18.0, '2023-05-21 00:00:00'),
        ];

        $this->taxService
            ->expects($this->once())
            ->method('getAllTaxes')
            ->willReturn($taxesData);

        // Act
        $response = $this->taxController->index(new Request());

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($taxesData, $response->getData());
    }

    public function testShow(): void
    {
        // Arrange
        $taxData = new TaxDTO(1, 'VAT', 20.0, '2023-05-21 00:00:00');

        $this->taxService
            ->expects($this->once())
            ->method('getTaxById')
            ->willReturn($taxData);

        // Act
        $response = $this->taxController->show(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals($taxData, $response->getData());
    }

    public function testShowNotFound(): void
    {
        // Arrange
        $this->taxService
            ->expects($this->once())
            ->method('getTaxById')
            ->willReturn(null);

        // Act
        $response = $this->taxController->show(new Request(), 1);

        // Assert
        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals(['error' => 'Tax not found', 'message' => 'Tax with id 1 not found'], $response->getData());
    }

    public function testCreate(): void
    {
        // Arrange
        $taxData = [
            'name' => 'VAT',
            'rate' => 20.0
        ];

        $request = new Request([], [], [], [], [], [], json_encode($taxData));

        $this->taxService
            ->expects($this->once())
            ->method('createTax');

        // Act
        $response = $this->taxController->create($request);

        // Assert
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals(['message' => 'Tax created successfully'], $response->getData());
    }

    public function testUpdate(): void
    {
        // Arrange
        $taxData = [
            'name' => 'VAT',
            'rate' => 22.0
        ];

        $tax = new TaxDTO(
            1,
            $taxData['name'],
            $taxData['rate'],
            '2023-05-21 00:00:00'
        );

        $request = new Request([], [], [], [], [], [], json_encode($taxData));

        $this->taxService
            ->expects($this->once())
            ->method('getTaxById')
            ->willReturn($tax);

        $this->taxService
            ->expects($this->once())
            ->method('updateTax');

        // Act
        $response = $this->taxController->update($request, 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Tax updated successfully'], $response->getData());
    }

    public function testDelete(): void
    {
        // Arrange
        $taxData = [
            'name' => 'VAT',
            'rate' => 20.0
        ];

        $tax = new TaxDTO(
            1,
            $taxData['name'],
            $taxData['rate'],
            '2023-05-21 00:00:00'
        );

        $this->taxService
            ->expects($this->once())
            ->method('getTaxById')
            ->willReturn($tax);

        $this->taxService
            ->expects($this->once())
            ->method('deleteTax');

        // Act
        $response = $this->taxController->delete(new Request(), 1);

        // Assert
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(['message' => 'Tax deleted successfully'], $response->getData());
    }
}
