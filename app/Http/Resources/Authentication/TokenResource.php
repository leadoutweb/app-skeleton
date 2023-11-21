<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->claims()->jti(),
            'type' => 'tokens',

            'attributes' => [
                'value' => $this->getValue(),
            ],
        ];
    }
}
