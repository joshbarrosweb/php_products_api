<?php

namespace App\Exceptions;

class ProductNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Product with ID {$id} not found");
    }
}
