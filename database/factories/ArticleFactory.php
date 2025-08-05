<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(6);
        return [
            'user_id' => \App\Models\User::factory(),
            'title' => $title,
            'slug' => \Illuminate\Support\Str::slug($title),
            'body' => $this->faker->paragraphs(5, true),
            'status' => 'published',
        ];
    }
}
