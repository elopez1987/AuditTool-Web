<?php

declare(strict_types=1);

namespace App\Services\Receipt;

enum VerificationSource: string
{
    case StockRoute = 'StockRoute';
    case CreditNote = 'CreditNote';

    public function foreignKey(): string
    {
        return match ($this) {
            self::StockRoute => 'receiptstock_id',
            self::CreditNote => 'receipt_stock_credit_id',
        };
    }
}
