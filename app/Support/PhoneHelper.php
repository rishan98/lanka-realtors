<?php

namespace App\Support;

class PhoneHelper
{
    public static function mask(?string $phone): string
    {
        if (! $phone) {
            return '';
        }

        $digits = preg_replace('/\D/', '', $phone);
        $total = strlen($digits);

        if ($total <= 4) {
            return str_repeat('X', $total);
        }

        $digitIndex = 0;
        $result = '';

        foreach (str_split($phone) as $char) {
            if (ctype_digit($char)) {
                if ($digitIndex < 4 || $digitIndex >= $total - 2) {
                    $result .= $char;
                } else {
                    $result .= 'X';
                }
                $digitIndex++;
            } else {
                $result .= $char;
            }
        }

        return $result;
    }
}
