<?php

declare(strict_types=1);

namespace App\Services\Receipt;

use Brick\Math\BigDecimal;
use Illuminate\Validation\ValidationException;

final class ReceiptQuantity
{
    public static function decimal(string|int $value): BigDecimal
    {
        return BigDecimal::of($value)->toScale(4);
    }

    public static function expected(?string $value): BigDecimal
    {
        if ($value === null || self::decimal($value)->isNegative()) {
            throw ValidationException::withMessages([
                'ExpectedLineId' => ['La línea esperada no tiene una cantidad válida; requiere conciliación.'],
            ]);
        }

        return self::decimal($value);
    }
}
