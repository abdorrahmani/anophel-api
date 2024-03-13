<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    /**
     * @return array{title: string, user_id: int, slug: string, body: string, poster: string, created_at: mixed, updated_at: mixed}
     */
    public function definition(): array
    {
        return [
            'title' => fake()->realText(20),
            "user_id" => 1,
            "slug" => fake()->slug(),
            "body" => fake()->paragraphs(10, true),
            "poster" => fake()->imageUrl(700 , 1200),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
