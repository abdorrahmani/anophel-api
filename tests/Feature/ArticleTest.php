<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    /**
     * @return void
     */
    public function test_can_list_articles(): void
    {
        Article::factory()->count(15)->create();

        $response = $this->getJson(route('v1.articles.index'));

        $response->assertOk();
        $response->assertJsonCount(15, 'data');

    }

    public function test_can_create_an_article(): void
    {
        $category = Category::factory()->create();
        $articleData = Article::factory()->raw([
            'title' => 'Article Title',
            "slug" => fake()->slug(),
            "body" => fake()->paragraphs(10, true),
            "poster" => UploadedFile::fake()->image('p.jpg'),
            'category_id' =>$category->id
        ]);
        $response = $this->postJson(route('v1.articles.store'), $articleData);

        $response->assertStatus(201);

    }

    public function test_can_show_single_article(): void
    {
        $article = Article::factory()->create();

        $response = $this->getJson(route('v1.articles.show', $article));

        $response->assertOk();
        $response->assertJson([
            'id' => $article->id,
        ]);
    }

    public function test_can_update_an_article(): void
    {
        $article = Article::factory()->create();
        $newArticleData = Article::factory()->raw();

        $response = $this->patchJson(route('v1.articles.update', $article), $newArticleData);

        $response->assertStatus(200);
    }
}
