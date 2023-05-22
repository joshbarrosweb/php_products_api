<?php

require_once 'vendor/autoload.php';

require_once 'src/Config/Database.php';
require_once 'src/Config/Router.php';
require_once 'src/Controllers/ProductController.php';
require_once 'src/Controllers/ProductTypeController.php';
require_once 'src/Controllers/TaxController.php';
require_once 'src/Controllers/SaleController.php';
require_once 'src/Repositories/ProductRepository.php';
require_once 'src/Repositories/ProductTypeRepository.php';
require_once 'src/Repositories/TaxRepository.php';
require_once 'src/Repositories/SaleRepository.php';
require_once 'src/Services/ProductService.php';
require_once 'src/Services/ProductTypeService.php';
require_once 'src/Services/TaxService.php';
require_once 'src/Services/SaleService.php';

use App\Config\Database;
use App\Repositories\ProductRepository;
use App\Repositories\ProductTypeRepository;
use App\Repositories\TaxRepository;
use App\Repositories\SaleRepository;

use App\Services\ProductService;
use App\Services\ProductTypeService;
use App\Services\SaleService;
use App\Services\TaxService;

use App\Controllers\ProductController;
use App\Controllers\ProductTypeController;
use App\Controllers\TaxController;
use App\Controllers\SaleController;

// Create a new instance of the database connection
$database = new Database('db', '5432', 'php_products_api', 'josuebarros1995', '12345678');

// Create the repository instances
$productRepository = new ProductRepository($database);
$productTypeRepository = new ProductTypeRepository($database);
$taxRepository = new TaxRepository($database);
$saleRepository = new SaleRepository($database);

// Create the service instances
$productService = new ProductService($productRepository);
$productTypeService = new ProductTypeService($productTypeRepository);
$taxService = new TaxService($taxRepository);
$saleService = new SaleService($saleRepository);

// Create instances of the controllers
$productController = new ProductController($productService);
$productTypeController = new ProductTypeController($productTypeService);
$taxController = new TaxController($taxService);
$saleController = new SaleController($saleService);

// Include the routes file
require_once './src/routes.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);
