<?php

namespace App\Models;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;

class FileAttributes
{

    public function __construct(
        protected UploadedFile $file,
        protected string $path,
        protected $lastModified = null,
        protected $overwrites = []
    )
    {
        //
    }

    public function toArray(): array
    {
        $lastModified = $this->lastModified / 1000 ?? now()->getTimestamp();

        return array_merge([
            'name' => $this->file->getClientOriginalName(),
            'url' => $this->path,
            'type' => $this->file->getClientOriginalExtension(),
            'size' => $this->file->getSize(),
            'last_modified' => Carbon::createFromTimestamp($lastModified),
        ], $this->overwrites);
    }
}
