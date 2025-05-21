<?php

namespace App\Providers;

use App\Models\Post; // Importar el modelo Post
use App\Models\User; // Importar el modelo User
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('update', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('delete', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });
    }
}
