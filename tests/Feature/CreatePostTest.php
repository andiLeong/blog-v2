<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Tests\Validate;

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
    public function title_must_be_valid()
    {
        $name = 'title';
        $rule = [
            'required' => 'The title field is required.',
            'string',
            'max:255' => 'The title must not be greater than 255 characters.',
            'unique:title:' . Post::class
        ];
        Validate::name($name)->against($rule)->through(
            fn($payload) => $this->createPost($payload)
        );

        $this->admin()->postJson('/api/posts', [])->assertJsonValidationMessageFor($name, null, 'The title field is required.');
    }

    /** @test */
    public function body_must_be_valid()
    {
        $name = 'body';
        $rule = ['required', 'string'];
        Validate::name($name)->against($rule)->through(
            fn($payload) => $this->createPost($payload)
        );

        $this->postJson('/api/posts', [])->assertJsonValidationErrorFor($name);
    }

    /** @test */
    public function tags_must_be_valid()
    {
        $name = 'tags';
        $rule = ['required', 'array'];
        Validate::name($name)->against($rule)->through(
            fn($payload) => $this->createPost($payload)
        );

        $this->postJson('/api/posts', [])->assertJsonValidationErrorFor($name);
    }

    /** @test */
    public function it_can_create_a_post_with_existing_tags()
    {
        $this->withoutExceptionHandling();
        $tags = create(Tag::class, [], 2);

        $this->get('api/posts')->assertDontSee($tags->pluck('name'));
        $this->createPost(['tags' => $tags->pluck('name')]);

        $this->get('api/posts')->assertSee([$tags[0]->name, $tags[1]->name]);
    }

    /** @test */
    public function it_can_create_a_post_with_non_existing_tags()
    {
        $this->withoutExceptionHandling();
        $tags = ['one', 'two'];

        $this->get('api/posts')->assertDontSee($tags);
        $this->createPost(['tags' => $tags]);
        $this->get('api/posts')->assertSee($tags);
    }

}
