<?php

namespace App\Validators;

class SaleValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['product_id']) || !is_numeric($data['product_id']) || $data['product_id'] <= 0) {
            $errors['product_id'] = 'Invalid product ID';
        }

        if (!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] <= 0) {
            $errors['quantity'] = 'Quantity must be a positive number';
        }

        return $errors;
    }
}
