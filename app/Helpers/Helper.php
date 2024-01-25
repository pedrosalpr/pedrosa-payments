<?php

declare(strict_types=1);

namespace App\Helpers;

class Helper
{
    public static function justDigits($value)
    {
        return preg_replace('/\D/', '', $value);
    }
}
