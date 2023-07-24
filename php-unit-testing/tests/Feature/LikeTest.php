<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{

    protected $post;

    public function setUp(): void
    {
        parent::setUp();

        $this->post = Post::factory()->create();
        $this->signIn();
    }
    /** @test */
    public function a_user_can_like_a_post()
    {
        $this->post->like();

        $this->assertDatabaseHas('likes', [
            'user_id' =>  $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post),
        ]);

        $this->assertTrue($this->post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
        $this->post->like();
        $this->post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' =>  $this->user->id,
            'likeable_id' => $this->post->id,
            'likeable_type' => get_class($this->post),
        ]);

        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_status()
    {

        $this->post->toggle();

        $this->assertTrue($this->post->isLiked());

        $this->post->toggle();
        $this->assertFalse($this->post->isLiked());
    }

    /** @test */
    public function a_post_may_know_how_many_likes_they_got()
    {

        $this->post->like();

        $this->assertEquals(1, $this->post->likesCount);
    }
}
