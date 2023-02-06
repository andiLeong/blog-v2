<?php

namespace Tests\Unit;

use App\Models\File;
use App\Models\Gallery;
use App\Models\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class VideoTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_load_the_files_relationship()
    {
        $video = Video::factory()->create();
        $file = File::factory()->create([
            'fileable_id' => $video->id,
            'fileable_type' => Model::getActualClassNameForMorph(Video::class),
        ]);
        $this->assertEquals($file->id, $video->files[0]->id);
    }
}
