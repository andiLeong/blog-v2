<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class PostTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function a_post_belongs_to_many_tags()
    {
        $post = create(Post::class);
        $tag = create(Tag::class);
        $post->tags()->attach($tag);
        $this->assertEquals($tag->name,$post->tags[0]->name);
    }
}
