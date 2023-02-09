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

    protected static $sourceModel;

    protected static function booted()
    {
        static::creating(function ($file) {
            $file->fileable_type ??= self::$sourceModel::class;
            $file->fileable_id ??= self::$sourceModel->id;
        });
    }

    public function fileable()
    {
        return $this->morphTo();
    }

    public function scopeWithRelation($query, $id, $model)
    {
        return $query
            ->where('fileable_id', $id)
            ->where('fileable_type', $model);
    }

    public function getUrlAttribute($value)
    {
        return Storage::disk('digitalocean')->url($value);
    }

    public function getSizeAttribute($value)
    {
        return round($value / 1000000, 2);
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

    /**
     * try to retrieve a source model
     *
     * @param string|null $model
     * @param string|int|null $id
     * @return Model
     * @throws \Exception
     */
    public static function setSourceModel(string|null $model, string|int|null $id): Model
    {
        $model = ucfirst(strtolower($model));
        $model = "\\App\\Models\\$model";

        if (!class_exists($model)) {
            throw new \Exception('Model isn\'t found ');
        }

        return self::$sourceModel = $model::findOrFail($id);
    }

    /**
     * getter for resource model
     * @return mixed
     */
    public static function getResourceModel()
    {
        return self::$sourceModel;
    }
}
