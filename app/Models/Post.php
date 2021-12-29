<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    protected $appends = ['shortDescription'];

    protected $guarded = [] ;

    protected static function booted()
    {
        static::creating(function ($model) {
            $model->slug = Str::slug($model->title);
        });
    }


    public function getShortDescriptionAttribute(): string
    {
        return Str::limit($this->body, 200);
    }
}
