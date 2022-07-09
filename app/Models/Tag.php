<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected static function booted()
    {
//        static::creating(function ($model) {
//            $model->slug = Str::slug($model->name);
//        });
//
//        static::updating(function ($model) {
//            $model->slug = Str::slug($model->name);
//        });
    }

    public function posts()
    {
       return $this->belongsToMany(Post::class,'post_tag','tag_id','post_id');
    }


    public function collectionFor(array $tags)
    {
        return $this->massiveInsert($tags)->whereIn('name', $tags)->get();
    }

    public function massiveInsert(array $tags)
    {
        Tag::upsert(
            array_map(fn($tag) => ['name' => $tag, 'slug' => $tag],$tags),
            ['name','slug'],
            ['name','slug']
        );
        return $this;
    }
}
