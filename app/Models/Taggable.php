<?php

namespace App\Models;

trait Taggable
{

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'post_tag', 'post_id', 'tag_id')->withTimestamps();
    }

    public function tag($tags)
    {
        return $this->tags()->syncWithoutDetaching($tags);
    }

    public function tagCollection($tags)
    {
        return $this->tags()->make()->collectionFor($tags);
    }
}
