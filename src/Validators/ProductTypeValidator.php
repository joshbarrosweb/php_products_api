<?php

namespace App\Validators;

class ProductTypeValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['name']) || empty(trim($data['name']))) {
            $errors['name'] = 'Name is required';
        }

        return $errors;
    }
}
