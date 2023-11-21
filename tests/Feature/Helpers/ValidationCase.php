<?php

namespace Tests\Feature\Helpers;

use Closure;

class ValidationCase
{
    /**
     * The overrides to the default parameters for the endpoint.
     */
    private array|Closure $overrides;

    /**
     * The field that is expected to have the validation error.
     */
    private ?string $field;

    /**
     * Instantiate the class.
     */
    public function __construct(array|Closure $overrides, string $field = null)
    {
        $this->overrides = $overrides;

        $this->field = $field;
    }

    /**
     * Instantiate a new validation case.
     */
    public static function make(array|Closure $overrides, string $field = null): static
    {
        return new static($overrides, $field);
    }

    /**
     * Get the overrides to the default parameters for the endpoint.
     */
    public function getOverrides(): array
    {
        if (! is_callable($this->overrides)) {
            return $this->overrides;
        }

        return $this->overrides = call_user_func($this->overrides);
    }

    /**
     * Get the field that is expected to have the validation error.
     */
    public function getField(): string
    {
        if ($this->field) {
            return $this->field;
        }

        return array_key_first($this->overrides);
    }
}
