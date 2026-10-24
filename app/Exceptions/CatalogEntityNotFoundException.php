<?php

namespace App\Exceptions;

use Exception;

class CatalogEntityNotFoundException extends Exception
{
    public static function centerAccess(string $username): self
    {
        return new self('El usuario no cuenta con Centro de Distribución asignado.'.$username);
    }
}
