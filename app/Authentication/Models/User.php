<?php

namespace App\Authentication\Models;

use App\Authentication\Concerns\MustBeActivated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasUuids, LogsActivity, MustBeActivated, Notifiable;

    /**
     * {@inheritdoc}
     */
    protected $dispatchesEvents = [
        'created' => Registered::class,
    ];

    /**
     * {@inheritdoc}
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $hidden = [
        'password',
    ];

    /**
     * {@inheritdoc}
     */
    protected $casts = [
        'password' => 'hashed',
        'activation_token' => 'hashed',
        'activated_at' => 'datetime',
    ];

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logOnly(array_diff($this->fillable, ['password']));
    }
}
