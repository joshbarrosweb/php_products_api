# PHP Products API REST

This is a simple PHP API for managing products. The project provides endpoints for managing products, product types, taxes, and sales. It follows the REST architecture and is built using PHP.

## Getting Started

To run the project, make sure you have Docker installed. Then, execute the following command in the root folder:


After that, you can access the application by visiting `http://localhost:8080/test` in your web browser. If the app is running properly, you will see the message "Hey, I'm alive!!!".

## Folder Structure

The project follows the following folder structure:

├── composer.json
├── composer.lock
├── docker-compose.yml
├── Dockerfile
├── index.php
├── init.sql
├── src
│ ├── Config
│ │ ├── Database.php
│ │ └── Router.php
│ ├── Controllers
│ │ ├── ProductController.php
│ │ ├── ProductTypeController.php
│ │ ├── SaleController.php
│ │ └── TaxController.php
│ ├── Dtos
│ │ ├── ProductDTO.php
│ │ ├── ProductTypeDTO.php
│ │ ├── SaleDTO.php
│ │ └── TaxDTO.php
│ ├── Exceptions
│ │ ├── ProductNotFoundException.php
│ │ ├── ProductTypeNotFoundException.php
│ │ ├── SaleNotFoundException.php
│ │ └── TaxNotFoundException.php
│ ├── Interfaces
│ │ ├── ProductRepositoryInterface.php
│ │ ├── ProductTypeRepositoryInterface.php
│ │ ├── SaleRepositoryInterface.php
│ │ └── TaxRepositoryInterface.php
│ ├── Models
│ │ ├── Product.php
│ │ ├── ProductType.php
│ │ ├── Sale.php
│ │ └── Tax.php
│ ├── Repositories
│ │ ├── ProductRepository.php
│ │ ├── ProductTypeRepository.php
│ │ ├── SaleRepository.php
│ │ └── TaxRepository.php
│ ├── routes.php
│ ├── Services
│ │ ├── ProductService.php
│ │ ├── ProductTypeService.php
│ │ ├── SaleService.php
│ │ └── TaxService.php
│ ├── Tests
│ │ └── unit
│ │ └── controllers
│ │ ├── ProductControllerTest.php
│ │ ├── ProductTypeControllerTest.php
│ │ ├── SaleControllerTest.php
│ │ └── TaxControllerTest.php
│ └── Validators
│ ├── ProductTypeValidator.php
│ ├── ProductValidator.php
│ ├── SaleValidator.php
│ └── TaxValidator.php
└── README.md


## Routes

The project provides the following routes:

- GET `/test`: A test route to check if the application is running properly.
- Products:
  - GET `/products`: Get all products.
  - GET `/products/{id}`: Get a specific product by ID.
  - POST `/products`: Create a new product.
  - PUT `/products/{id}`: Update an existing product.
  - DELETE `/products/{id}`: Delete a product.
- Product Types:
  - GET `/product-types`: Get all product types.
  - GET `/product-types/{id}`: Get a specific product type by ID.
  - POST `/product-types`: Create a new product type.
  - PUT `/product-types/{id}`: Update an existing product type.
  - DELETE `/product-types/{id}`: Delete a product type.
- Taxes:
  - GET `/taxes`: Get all taxes.
  - GET `/taxes/{id}`: Get a specific tax by ID.
  - POST `/taxes`: Create a new tax.
  - PUT `/taxes/{id}`: Update an existing tax.
  - DELETE `/taxes/{id}`: Delete a tax.
- Sales:
  - GET `/sales`: Get all sales.
  - GET `/sales/{id}`: Get a specific sale by ID.
  - POST `/sales`: Create a new sale.
  - PUT `/sales/{id}`: Update an existing sale.
  - DELETE `/sales/{id}`: Delete a sale.

## License

This project is licensed under the [MIT License](LICENSE).
