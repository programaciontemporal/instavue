<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ExploreController;
use App\Http\Controllers\SavedPostController;
use Illuminate\Support\Facades\Auth;

// Ruta para la página de bienvenida (home)
Route::get('/', function () {
    if (! Auth::check()) {
        return redirect()->route('auth.combined');
    }
    return redirect()->route('feed');
})->name('home');

// Incluye las rutas de configuración desde un archivo separado
require __DIR__ . '/settings.php';
// Incluye las rutas de autenticación desde un archivo separado
require __DIR__ . '/auth.php';

// Grupo de rutas protegidas
Route::middleware(['auth', 'verified'])->group(function () {
    // Rutas para el perfil de usuario
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para ver un perfil de usuario específico
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    // Rutas para publicaciones
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    // Rutas para mostrar, editar, actualizar y eliminar posts (AÑADIDAS)
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::patch('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');


    // Rutas para el feed principal
    Route::get('/feed', [HomeController::class, 'index'])->name('feed');

    // Rutas para seguir/dejar de seguir
    Route::post('/profile/{user}/follow', [FollowController::class, 'store'])->name('follow');
    Route::delete('/profile/{user}/unfollow', [FollowController::class, 'destroy'])->name('unfollow');

    // Rutas para comentarios
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Rutas para "Me gusta"
    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('likes.store');
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('likes.destroy');

    // Rutas para Guardar/Desguardar Publicaciones
    Route::post('/posts/{post}/save', [SavedPostController::class, 'store'])->name('posts.save');
    Route::delete('/posts/{post}/unsave', [SavedPostController::class, 'destroy'])->name('posts.unsave');

    // Ruta para la página de explorar
    Route::get('/explore', [ExploreController::class, 'index'])->name('explore');
});
