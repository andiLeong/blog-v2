<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;
use Tests\Validate;

class UpdatePostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function update_post_title_must_be_valid()
    {
        $rule = [
            'required' => 'The title field is required.',
            'string',
            'max:255' => 'The title must not be greater than 255 characters.',
            'unique:title:' . Post::class
        ];
        Validate::name('title')->against($rule)->through(
            fn($payload) => $this->updatePost($payload)
        );
    }

    /** @test */
    public function no_validation_error_if_update_with_same_title()
    {
        $post = create(Post::class);
        $this->updatePost([], $post)->assertJsonMissingValidationErrors('title');
    }

    /** @test */
    public function body_must_be_valid()
    {
        $name = 'body';
        $rule = ['required', 'string'];
        Validate::name($name)->against($rule)->through(
            fn($payload) => $this->updatePost($payload)
        );

        $post = create(Post::class);
        $this->admin()->patchJson('/api/posts/' . $post->title, [])->assertJsonValidationErrorFor($name);
    }

    /** @test */
    public function tags_must_be_valid()
    {
        $name = 'tags';
        $rule = ['required', 'array'];
        Validate::name($name)->against($rule)->through(
            fn($payload) => $this->updatePost($payload)
        );

        $post = create(Post::class);
        $this->admin()->patchJson('/api/posts/' . $post->title, [])->assertJsonValidationErrorFor($name);
    }

    /** @test */
    public function it_can_update_a_post()
    {
        $response = $this->updatePost([
            'title' => 'new',
            'body' => 'body',
            'tags' => $this->newTags()
        ])->assertStatus(200);

        $this->assertEquals('new', $response['title']);
        $this->assertEquals('body', $response['body']);
    }

    /** @test */
    public function it_can_update_post_and_its_tags()
    {
        $tags = $this->newTags();
        $post = create(Post::class);
        $post->tag(create(Tag::class));
        $this->assertNotEquals($tags,
            $post->tags->pluck('name')->all()
        );

        $this->updatePost(['tags' => $tags], $post);

        $this->assertEquals($tags,
            $post->refresh()->tags->pluck('name')->all()
        );
    }

    public function newTags()
    {
        return ['a', 'b'];
    }

    public function updatePost($payload = [], Post $post = null)
    {
        $post ??= create(Post::class);

        return $this->admin()->patchJson("/api/posts/{$post->slug}", array_merge([
            'title' => 'new',
            'body' => 'body',
            'tags' => $this->newTags()
        ], $payload));
    }
}
