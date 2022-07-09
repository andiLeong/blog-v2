<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory, Taggable;

    protected $appends = ['shortDescription'];

    protected $guarded = [];

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });

        static::updating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }


    public function getShortDescriptionAttribute(): string
    {
        return Str::limit(
            html_entity_decode(strip_tags($this->body))
        );
    }

    /**
     * get the data when store a post
     * @param array $data
     * @return array
     */
    public function dataOfCreation(array $data)
    {
        return collect($data)->except('tags')->merge([
            'user_id' => auth()->id()
        ])->all();
    }

    /**
     * create post action
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        $tags = $this->tags()->make()->collectionFor($data['tags']);
        return tap(
            Post::create($this->dataOfCreation($data))
        )->tag($tags);
    }

}
