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


Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
})->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';


Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Ruta para ver un perfil de usuario específico
    Route::get('/profile/{user}', [ProfileController::class, 'show'])->name('profile.show');

    // Rutas para publicaciones
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Rutas para el feed
    Route::get('/feed', [HomeController::class, 'index'])->name('feed');

    // Rutas para seguir/dejar de seguir
    Route::post('/profile/{user}/follow', [FollowController::class, 'store'])->name('follow');
    Route::delete('/profile/{user}/unfollow', [FollowController::class, 'destroy'])->name('unfollow');

    // Rutas para comentarios
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

    // Da 'me gusta' a una publicación.
    Route::post('/posts/{post}/likes', [LikeController::class, 'store'])->name('likes.store');

    // Quita el 'me gusta' de una publicación.
    Route::delete('/posts/{post}/likes', [LikeController::class, 'destroy'])->name('likes.destroy');

    // Ruta para la página de explorar
    Route::get('/explore', [\App\Http\Controllers\ExploreController::class, 'index'])->name('explore');
});