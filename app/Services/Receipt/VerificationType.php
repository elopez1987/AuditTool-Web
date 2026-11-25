<?php

declare(strict_types=1);

namespace App\Services\Receipt;

enum VerificationType: string
{
    case Rotary = 'TRP-01';
    case Definitive = 'TRP-02';
}
