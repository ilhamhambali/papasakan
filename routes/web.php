<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\FavoriteController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route untuk Halaman Depan (Publik)
Route::get('/', [RecipeController::class, 'index'])->name('home');

Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
Route::get('/recipes/{recipe:slug}', [RecipeController::class, 'show'])->name('recipes.show');

// Grup route untuk aksi lain yang butuh login
Route::middleware('auth')->group(function () {
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.mine');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{recipe}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{recipe}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{recipe}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    Route::get('/my-favorites', [FavoriteController::class, 'index'])->name('favorites.index');
    Route::post('/recipes/{recipe}/favorite', [FavoriteController::class, 'toggle'])->name('favorites.toggle');

    // [BARU] Route untuk CRUD Artikel
    Route::get('/my-articles', [ArticleController::class, 'myArticles'])->name('articles.mine');
    Route::get('/articles/create', [ArticleController::class, 'create'])->name('articles.create');
    Route::post('/articles', [ArticleController::class, 'store'])->name('articles.store');
    Route::get('/articles/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
    Route::put('/articles/{article}', [ArticleController::class, 'update'])->name('articles.update');
    Route::delete('/articles/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
});

Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{article:slug}', [ArticleController::class, 'show'])->name('articles.show');

// Route bawaan Breeze untuk Dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Route bawaan Breeze untuk Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
