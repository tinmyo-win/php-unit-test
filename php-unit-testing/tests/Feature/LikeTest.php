<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeTest extends TestCase
{
    /** @test */
    public function a_user_can_like_a_post()
    {
        $post = Post::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $post->like();

        $this->assertDatabaseHas('likes', [
            'user_id' =>  $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post),
        ]);

        $this->assertTrue($post->isLiked());
    }

    /** @test */
    public function a_user_can_unlike_a_post()
    {
        $post = Post::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $post->like();
        $post->unlike();

        $this->assertDatabaseMissing('likes', [
            'user_id' =>  $user->id,
            'likeable_id' => $post->id,
            'likeable_type' => get_class($post),
        ]);

        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_user_may_toggle_a_post_like_status()
    {
        $post = Post::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $post->toggle();

        $this->assertTrue($post->isLiked());

        $post->toggle();
        $this->assertFalse($post->isLiked());
    }

    /** @test */
    public function a_post_may_know_how_many_likes_they_got()
    {
        $post = Post::factory()->create();

        $user = User::factory()->create();

        $this->actingAs($user);

        $post->like();

        $this->assertEquals(1, $post->likesCount);
    }
}
