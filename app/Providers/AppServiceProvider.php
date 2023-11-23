<?php

namespace App\Providers;

use App\Authentication\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Factory::guessModelNamesUsing(function ($foo) {
            return Str::of(get_class($foo))
                ->replace('Database\Factories', 'App')
                ->remove('Factory')
                ->replaceLast('\\', '\Models\\')
                ->toString();
        });

        Factory::guessFactoryNamesUsing(function ($class) {
            return Str::of($class)
                ->replace('App', 'Database\\Factories')
                ->remove('\Models')
                ->append('Factory')
                ->toString();
        });

        Builder::macro('apiPaginate', fn () => $this->paginate(
            perPage: request()->collect('page')->get('size'),
            page: request()->collect('page')->get('number')
        ));

        Relation::enforceMorphMap([
            'users' => User::class,
        ]);
    }
}
