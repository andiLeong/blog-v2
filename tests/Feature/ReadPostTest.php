<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ReadPostTest extends TestCase
{
    use LazilyRefreshDatabase;

    private $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->post = create(Post::class, ['title' => 'a title', 'slug' => 'a-title']);
    }

    /** @test */
    public function it_can_render_a_list_of_posts()
    {
        $posts = Post::factory()->count(10)->create();
        $response = $this->get('/api/posts?perPage=10')->assertStatus(200)->json();
        $this->assertCount($posts->count(), $response['data']);
    }

    /** @test */
    public function it_can_render_a_list_of_posts_with_its_tags()
    {
        $posts = create(Post::class,[],2);
        $tag = create(Tag::class);
        $posts[0]->tags()->attach($tag);
        $this->get('/api/posts')->assertSee($tag->name);
    }

    /** @test */
    public function it_can_get_a_post()
    {
        $this->get('/api/posts/a-title')
            ->assertStatus(200)
            ->assertJson([
                'title' => 'a title',
            ]);
    }

    /** @test */
    public function it_can_get_a_post_with_its_tags()
    {
        $tag = create(Tag::class);
        $this->post->tags()->attach($tag);

        $this->get('/api/posts/a-title')
            ->assertSee($tag->name);
    }
}
