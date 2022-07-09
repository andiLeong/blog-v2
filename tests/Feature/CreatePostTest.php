<?php

namespace Tests\Feature;

use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class CreatePostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_create_post()
    {
        $this->assertDatabaseCount('posts', 0);
        $this->createPost();
        $this->assertDatabaseCount('posts', 1);
    }

    /** @test */
    public function non_authenticate_user_cant_create_post()
    {
        $this->postJson('/api/posts', [])->assertStatus(401);
    }

    /** @test */
    public function non_admin_user_cant_create_post()
    {
        $this->signIn()->postJson('/api/posts', [])->assertStatus(403);
    }

    /** @test */
    public function it_can_create_a_post_with_existing_tags()
    {
        $this->withoutExceptionHandling();
        $tags = create(Tag::class, [], 2);

        $this->get('api/posts')->assertDontSee($tags->pluck('name'));


        $this->createPost($tags->pluck('name'));

        $this->get('api/posts')->assertSee([$tags[0]->name, $tags[1]->name]);
    }

    /** @test */
    public function it_can_create_a_post_with_non_existing_tags()
    {
        $this->withoutExceptionHandling();
        $tags = ['one', 'two'];

        $this->get('api/posts')->assertDontSee($tags);
        $this->createPost($tags);
        $this->get('api/posts')->assertSee($tags);
    }
}
