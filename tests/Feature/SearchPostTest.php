<?php

namespace Tests\Feature;

use App\Models\Post;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class SearchPostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_search_posts()
    {
        $this->withoutExceptionHandling();
        create(Post::class, [
            'title' => 'tailwind is awesome',
            'body' => 'body'
        ]);
        create(Post::class, [
            'title' => 'a title',
            'body' => 'laravel is'
        ]);

        $tailwind = $this->get('api/posts/search?key=tail')->assertSee('tailwind')->json();
        $this->assertCount(1, $tailwind);

        $response = $this->get('api/posts/search?key=vel')->assertSuccessful()->assertSee('laravel')->json();
        $this->assertCount(1, $response);
    }
}
