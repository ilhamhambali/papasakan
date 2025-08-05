<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Recipe;
use App\Models\Article;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus user seeder bawaan jika tidak perlu
        // User::factory(10)->create();

        // Buat satu user spesifik untuk kita login
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Buat beberapa kategori
        $categories = Category::factory(5)->create();

        // Buat 30 resep, masing-masing milik user acak (atau user spesifik kita)
        Recipe::factory(30)->create([
            'user_id' => $user->id
        ])->each(function ($recipe) use ($categories) {
            // Lampirkan 1-3 kategori secara acak ke setiap resep
            $recipe->categories()->attach(
                $categories->random(rand(1, 3))->pluck('id')->toArray()
            );
        });

        Article::factory(10)->create([
            'user_id' => $user->id
        ]);
    }
}
