<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_render_a_list_of_posts()
    {
        $posts = Post::factory()->count(10)->create();
        $response = $this->get('/api/posts?perPage=10');
        $body = json_decode($response->content(),true);

        $response->assertStatus(200);
        $this->assertCount($posts->count(),$body['data']);
    }

    /** @test */
    public function it_can_get_a_post()
    {
        Post::factory()->create(['title' => 'a title','slug' => 'a-title']);
        $this->get('/api/posts/a-title')
            ->assertStatus(200)
            ->assertJson([
                'title' => 'a title',
            ]);
    }
}
