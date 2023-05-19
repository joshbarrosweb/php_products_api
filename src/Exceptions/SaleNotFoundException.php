<?php

namespace App\Exceptions;

class SaleNotFoundException extends Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Sale with ID {$id} not found");
    }
}
