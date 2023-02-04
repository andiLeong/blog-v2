<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory;

    protected $appends = ['age'];
    protected $guarded = [];
    protected $casts = [
        'pinned' => "boolean",
        'last_modified' => "datetime",
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

    public function getAgeAttribute()
    {
        $lastModified = $this->last_modified;
        $dob = Carbon::parse(Gallery::DOB);
        $day = Str::pluralWords('day', $lastModified->diff($dob)->format('%d'));
        $month = Str::pluralWords('month', $lastModified->diff($dob)->format('%m'));
        $year = Str::pluralWords('year', $lastModified->diff($dob)->format('%y'));

        return "$year, $month, $day";
    }
}
