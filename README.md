# PHP Products API REST

This is a simple PHP API for managing products. The project provides endpoints for managing products, product types, taxes, and sales. It follows the REST architecture and is built using PHP.

## Getting Started

To run the project, make sure you have Docker installed. Then, execute the following command in the root folder:


After that, you can access the application by visiting `http://localhost:8080/test` in your web browser. If the app is running properly, you will see the message "Hey, I'm alive!!!".

## Files
Here is a brief overview of the role of each file in this project:

composer.json and composer.lock: These files contain the project's PHP dependencies.
docker-compose.yml and Dockerfile: These files define the Docker configuration for running the project.
index.php: This is the entry point of the application, responsible for processing incoming requests.
init.sql: This file contains the SQL commands to create the necessary database structure.
README.md: This file, containing documentation about the project, including how to get started and the provided routes.
src
This directory contains the source code for the project.

Config: Contains configuration files:
Database.php: Defines the database connection settings.
Router.php: Defines the routes of the application.
Controllers: Contains the application's controllers, responsible for handling incoming requests and returning responses.
Dtos: Contains the Data Transfer Objects (DTOs), which define how data will be sent and received.
Exceptions: Contains custom exceptions that may be thrown in the application.
Interfaces: Contains the interfaces that the repository classes must implement.
Models: Contains the application's models, representing the data in the database.
Repositories: Contains the repository classes, responsible for handling the logic for database access.
routes.php: Defines the application's routes.
Services: Contains service classes, which encapsulate business logic.
Tests: Contains the application's tests.
Validators: Contains the classes that handle validation of data.

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
