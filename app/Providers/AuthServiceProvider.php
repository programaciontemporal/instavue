<?php

namespace App\Providers;

use App\Models\Post;
use App\Models\User;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; // Esta línea ya no es necesaria si no defines Gates explícitamente

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Post::class => PostPolicy::class, // La política para el modelo Post está correctamente registrada
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Las definiciones de Gate aquí son redundantes y pueden causar conflicto si ya tienes políticas
        // o si el middleware 'can' está intentando usar una política.
        // Con la política PostPolicy registrada arriba, Laravel sabrá cómo autorizar 'update' y 'delete'
        // para el modelo Post.
        /*
        Gate::define('update', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });

        Gate::define('delete', function (User $user, Post $post) {
            return $user->id === $post->user_id;
        });
        */
    }
}
