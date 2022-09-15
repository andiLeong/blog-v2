<?php

namespace App\Practice\Observers\Subject;

use App\Models\Post;

class PostWasCreated implements Subject
{
    use Subjectable;

    public function __construct(public Post $post)
    {
        //
    }
}
