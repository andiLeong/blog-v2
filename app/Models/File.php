<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $guarded = [] ;
    protected $casts = [
        'pinned' => "boolean",
    ];

    public function fileable()
    {
        return $this->morphTo();
    }

    public function scopeWithRelation($query,$id,$model)
    {
        return $query
            ->where('fileable_id',$id)
            ->where('fileable_type',Model::getActualClassNameForMorph($model));
    }

    public function getUrlAttribute($value)
    {
        return Storage::disk('digitalocean')->url($value);
    }


    public function getSizeAttribute($value)
    {
        return round($value / 1000000, 2 );
    }

}
