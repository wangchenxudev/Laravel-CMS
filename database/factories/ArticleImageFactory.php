<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<ArticleImage>
 */
class ArticleImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $disk = (string) config('articles.image_disk', 'public');
        $name = fake()->unique()->lexify('image-????????').'.jpg';
        $path = UploadedFile::fake()->image($name, 640, 360)->store('article-images/demo', $disk);

        return [
            'article_id' => Article::factory(),
            'disk' => $disk,
            'path' => $path,
            'original_name' => $name,
            'position' => 1,
        ];
    }
}
