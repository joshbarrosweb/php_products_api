<?php

namespace App\Validators;

class TaxValidator
{
    public static function validate(array $data): array
    {
        $errors = [];

        if (!isset($data['name']) || empty(trim($data['name']))) {
            $errors['name'] = 'Name is required';
        }

        if (!isset($data['rate']) || !is_numeric($data['rate']) || $data['rate'] <= 0) {
            $errors['rate'] = 'Rate must be a positive number';
        }

        return $errors;
    }
}
