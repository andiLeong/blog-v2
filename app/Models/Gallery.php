<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;

    public const DOB = '2020-04-16';

    public function files()
    {
        return $this->morphMany(File::class,'fileable');
    }
}
