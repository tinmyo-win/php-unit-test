<?php

namespace Tests\Unit;

use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class ArticleTest extends TestCase
{

    use RefreshDatabase;

    /** @test */
    public function it_feteches_trending_articles()
    {
        Article::factory(3)->create();
        Article::factory()->create(['reads' => 10 ]);
        $mostPopular = Article::factory()->create(['reads' => 20 ]);

        $articles = Article::trending()->get();

        $this->assertEquals($mostPopular->id , $articles->first()->id);
        $this->assertCount(3, $articles);
    }
}
