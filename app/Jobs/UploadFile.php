<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class UploadFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $path,
        protected array  $attributes
    )
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(Filesystem $fileManager)
    {
        $path = $fileManager->putFileAs('test', $this->path, Str::uuid() . '.' . $this->attributes['type'], 'public');
        if (!$path) {
            throw new \Exception('Fail to upload the file');
        }

        tap(File::create($this->attributes + ['url' => $path]), fn($file) =>
            unlink($this->path)
        );
    }
}
