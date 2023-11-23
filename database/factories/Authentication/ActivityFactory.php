<?php

namespace Database\Factories\Authentication;

use Illuminate\Database\Eloquent\Factories\Factory;
use Spatie\Activitylog\Models\Activity;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Spatie\Activitylog\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * {@inheritdoc}
     */
    protected $model = Activity::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'description' => 'created',
        ];
    }
}
