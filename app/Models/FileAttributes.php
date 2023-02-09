<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class FileAttributes
{

    public static function make(UploadedFile $file, $lastModified = null, $overwrites = []): array
    {
        $lastModified = $lastModified / 1000 ?? now()->getTimestamp();

        $model = File::getResourceModel();
        return array_merge([
            'name' => $file->getClientOriginalName(),
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'last_modified' => Carbon::createFromTimestamp($lastModified),
            'fileable_type' => $model::class,
            'fileable_id' => $model->id,
        ], $overwrites);
    }
}
