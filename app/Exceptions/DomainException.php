<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

abstract class DomainException extends Exception
{
    /**
     * Render the exception.
     */
    public function render(): JsonResponse
    {
        return response()->json(['error' => $this->getError(), 'data' => $this->getData()], 400);
    }

    /**
     * Get the error of the exception.
     */
    protected function getError(): string
    {
        return Str::of(get_class($this))->afterLast('\\')->beforeLast('Exception')->snake()->upper()->toString();
    }

    /**
     * Get any additional data to render with the exception.
     */
    protected function getData(): array
    {
        return [];
    }
}
