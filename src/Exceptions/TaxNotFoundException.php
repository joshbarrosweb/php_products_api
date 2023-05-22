<?php

namespace App\Exceptions;

class TaxNotFoundException extends \Exception
{
    public function __construct(int $id)
    {
        parent::__construct("Tax with ID {$id} not found");
    }
}
