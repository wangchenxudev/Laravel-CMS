<?php

namespace Database\Factories;

use App\Enums\ArticleStatus as ArticleStatusEnum;
use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Article>
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
        $title = fake()->sentence(4);

        return [
            'author_id' => User::factory(),
            'title' => $title,
            'slug' => Str::slug($title) ?: 'article',
            'summary' => fake()->paragraph(),
            'content' => fake()->paragraphs(3, true),
            'status' => ArticleStatusEnum::Draft,
        ];
    }
}
