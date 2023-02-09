<?php

namespace Tests;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Mockery\MockInterface;

trait FileCanBeUploaded
{

    public function upload(Model $model, $name = 'avatar.jpeg', $mockReturn = null, \DateTimeInterface $lastModified = null)
    {
        Config::set('queue.default', 'sync');
        $file = UploadedFile::fake()->image($name);

        $this->mock(Filesystem::class, fn(MockInterface $mock) =>
            $mock->shouldReceive('putFileAs')
                ->once()
                ->andReturn($mockReturn === null ? $file->name : $mockReturn)
        );

        return $this->be(admin())->postJson('/api/files', [
            'fileable_id' => $model->getKey(),
            'fileable_type' => class_basename($model),
            'file' => $file,
            'last_modified' => $lastModified !== null
                ? $lastModified->getTimestamp() * 1000
                : now()->subDays(20)->timestamp * 1000,
        ]);
    }
}
