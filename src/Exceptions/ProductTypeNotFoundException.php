<?php

namespace App\Exceptions;

class ProductTypeNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Product type with ID {$id} not found");
    }
}
