<?php

namespace App\Validators;

class ProductValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['name']) || empty(trim($data['name']))) {
            $errors['name'] = 'Name is required';
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Price must be a positive number';
        }

        if (!isset($data['quantity']) || !is_numeric($data['quantity']) || $data['quantity'] < 0) {
            $errors['quantity'] = 'Quantity must be a non-negative number';
        }

        return $errors;
    }
}
