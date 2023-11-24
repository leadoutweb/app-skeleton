<?php

namespace Database\Factories\Authentication;

use App\Authentication\Models\User;
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
            'event' => 'created',
            'properties' => ['name' => 'Henrik', 'email' => 'henrik@example.com'],

            'subject_id' => User::factory(),
            'subject_type' => 'users',
            'causer_id' => User::factory(),
            'causer_type' => 'users',
        ];
    }
}
