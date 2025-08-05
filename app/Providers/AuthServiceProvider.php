<?php

namespace App\Providers;

use App\Models\Recipe;
use App\Models\Article;
use App\Policies\RecipePolicy;
use App\Policies\ArticlePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Recipe::class => RecipePolicy::class,
        Article::class => ArticlePolicy::class, // [BARU] Tambahkan ini
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Baris ini tidak perlu diubah, biarkan seperti ini
    }
}
