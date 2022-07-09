<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class UpdatePostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_update_a_post()
    {
        $post = $this->createPost()->json();
        $response = $this->patchJson("/api/posts/{$post['slug']}",[
            'title' => 'new',
            'body' => 'body',
            'tags' => $this->newTags()
        ])->assertStatus(200);

        $this->assertEquals('new',$response['title']);
        $this->assertEquals('body',$response['body']);
    }

    /** @test */
    public function it_can_update_post_and_its_tags()
    {
        $tags = $this->newTags();
        $this->withoutExceptionHandling();
        $post = $this->createPost()->json();
        $postModel = Post::with('tags')->find($post['id']);
        $this->assertNotEquals($tags,
            $postModel->tags->pluck('name')->all()
        );

        $updatedPost = $this->patchJson("/api/posts/{$post['slug']}",[
            'title' => 'new',
            'body' => 'body',
            'tags' => $tags
        ])->json();

        $this->assertEquals($tags,
            $postModel->refresh()->tags->pluck('name')->all()
        );
        $this->assertEquals('new',$updatedPost['title']);
    }

    public function newTags()
    {
        return ['a','b'];
    }
}
