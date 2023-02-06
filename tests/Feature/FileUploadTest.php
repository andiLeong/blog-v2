<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Gallery;
use App\Models\Video;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\FileCanBeUploaded;
use Tests\TestCase;

class FileUploadTest extends testcase
{
    use LazilyRefreshDatabase;
    use FileCanBeUploaded;

    /** @test */
    public function it_will_get_404_if_morph_model_is_not_found()
    {
        $this->be(admin())->postJson('/api/files', [
            'fileable_id' => 100,
            'fileable_type' => 'not a model',
        ])->assertStatus(404);
    }

    /** @test */
    public function it_response_with_502_if_upload_fails()
    {
        $gallery = Gallery::factory()->create();
        $this->upload($gallery, 'avatar.jpeg', false)->assertStatus(502);
    }

    /** @test */
    public function it_can_upload_a_gallery_photo()
    {
        $gallery = Gallery::factory()->create();
        $this->assertEquals(0, File::count());

        $this->upload($gallery)->assertSuccessful();

        $file = File::first();
        $this->assertNotNull($file);
        $this->assertEquals($gallery->id, $file->fileable_id);
        $this->assertEquals('App\Models\Gallery', $file->fileable_type);
    }

    /** @test */
    public function it_can_upload_a_video()
    {
        $video = Video::factory()->create();
        $this->assertEquals(0, File::count());

        $this->upload($video, 'avatar.mp4')->assertSuccessful();

        $file = File::first();
        $this->assertNotNull($file);
        $this->assertEquals($video->id, $file->fileable_id);
        $this->assertEquals('App\Models\Video', $file->fileable_type);
    }
}
