<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    public static function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = trim((string) ($data[$field] ?? ''));

            foreach ($fieldRules as $rule) {
                if ($rule === 'required' && $value === '') {
                    $errors[$field][] = "الحقل {$field} مطلوب.";
                }

                if ($rule === 'email' && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field][] = "الحقل {$field} يجب أن يكون بريدًا إلكترونيًا صالحًا.";
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) explode(':', $rule)[1];
                    if (mb_strlen($value) < $min) {
                        $errors[$field][] = "الحقل {$field} يجب ألا يقل عن {$min} أحرف.";
                    }
                }
            }
        }

        return $errors;
    }
}
