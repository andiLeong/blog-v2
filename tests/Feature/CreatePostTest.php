<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
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

    /**
     * @dataProvider titleProvider
     * @test
     */
    public function title_must_be_valid($payload)
    {
        $this->createPost($payload)->assertJsonValidationErrorFor('title');
    }

    /**
     * @dataProvider bodyProvider
     * @test
     */
    public function body_must_be_valid($payload)
    {
        $this->createPost($payload)->assertJsonValidationErrorFor('body');
    }

    /**
     * @dataProvider tagsProvider
     * @test
     */
    public function tags_must_be_valid($payload): void
    {
        $this->createPost($payload)->assertJsonValidationErrorFor('tags');
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

    public function tagsProvider()
    {
        return [
            [['tags' => null]],
            [['tags' => '']],
            [['tags' => 'foo']],
            [['tags' => '  ']],
            [['tags' => 33]],
            [['tags' => true]],
        ];
    }

    public function bodyProvider()
    {
        return[
            [['body' => null]],
            [['body' => '']],
            [['body' => '  ']],
            [['body' => ['foo' => 'bar']]],
            [['body' => 33]],
            [['body' => true]],
        ];
    }

    public function titleProvider()
    {
//        $post = create(Post::class);
        return [
            [['title' => null]],
            [['title' => '']],
            [['title' => '  ']],
//            [['title' => $post->title]],
            [['title' => Str::random(256)]],
            [['title' => ['foo']]],
            [['title' => 34]],
            [['title' => true]],
        ];
    }
}
