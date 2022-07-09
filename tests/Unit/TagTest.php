<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\Tag;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TagTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function tag_consist_of_the_following_attributes()
    {
        $tag = create(Tag::class,[
            'name' => 'laravel',
            'slug' => 'laravel',
        ]);
        $this->assertEquals('laravel',$tag->name);
        $this->assertEquals('laravel',$tag->slug);
    }

    /** @test */
    public function a_tag_can_have_many_posts()
    {
        $tag = create(Tag::class,[
            'name' => 'laravel',
            'slug' => 'laravel',
        ]);

        $post = create(Post::class);
        $post2 = create(Post::class);

        $post->tags()->attach($tag);
        $post2->tags()->attach($tag);


        $this->assertEquals($post->title,$tag->posts[0]->title);
        $this->assertEquals($post2->title,$tag->posts[1]->title);
    }
}
