<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class BusinessException extends Exception
{
    public function __construct(
        string $userMessage,
        private readonly ?string $internalDetail = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($userMessage, $code, $previous);
    }

    public function internalDetail(): ?string
    {
        return $this->internalDetail;
    }

    /**
     * Evita que Laravel reporte esta excepción una segunda vez con el logger
     * por defecto: quien la lanza ya deja el detalle técnico relevante en el log.
     */
    public function report(): bool
    {
        return true;
    }

    public function render(Request $request): RedirectResponse|JsonResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
            ], $this->getCode() ?: 422);
        }

        return redirect()->back()->withInput()->with('error', $this->getMessage());
    }
}
