<?php

namespace App\Http\Resources\Authentication;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => 'activity',

            'attributes' => [
                'description' => $this->description,
                'event' => $this->event,
                'properties' => $this->properties,
                'subject_name' => $this->subject->name,
                'causer_name' => $this->causer?->name,

                'subject_id' => $this->subject_id,
                'subject_type' => $this->subject_type,
                'causer_id' => $this->causer_id,
                'causer_type' => $this->causer_type,

                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
            ],
        ];
    }
}
