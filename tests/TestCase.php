<?php

namespace Tests;

use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function signIn(User $user = null, Array $attributes = null)
    {
        $user ??= create(User::class,$attributes ??= []);
        $this->actingAs($user);
        return $this;
    }

    public function admin()
    {
        return $this->signIn(admin());
    }

    public function createPost($tags = null)
    {
        return $this->admin()->postJson('/api/posts',
            $this->postAttributes($tags)
        );
    }

    public function postAttributes($tags = null)
    {
        $attributes = make(Post::class)->toArray();
        $tags ??= create(Tag::class,[],2)->pluck('name');

        return array_merge( $attributes, [
            'tags' => $tags
        ]);
    }
}
