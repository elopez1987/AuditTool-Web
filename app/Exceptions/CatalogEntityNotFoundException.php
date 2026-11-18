<?php

namespace App\Exceptions;

use Exception;

class CatalogEntityNotFoundException extends Exception
{
    public static function centerAccess(string $username): self
    {
        return new self('El usuario no cuenta con Centro de Distribución asignado.'.$username);
    }

    public static function partNumber(string $partNumber): self
    {
        return new self('El número de parte buscado no existe :'.$partNumber);
    }
}
