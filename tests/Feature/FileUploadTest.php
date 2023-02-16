<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Gallery;
use App\Models\Video;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\FileCanBeUploaded;
use Tests\TestCase;

class FileUploadTest extends testcase
{
    use LazilyRefreshDatabase;
    use FileCanBeUploaded;

    /** @test */
    public function only_admin_can_upload_file()
    {
        $this->signIn()->postJson('/api/files')->assertStatus(403);
    }

    /** @test */
    public function file_must_be_a_file()
    {
        $this->fire(['file' => 'not-a-file'])->assertJsonValidationErrorFor('file');
        $this->fire(['file' => 9])->assertJsonValidationErrorFor('file');
        $this->fire(['file' => true])->assertJsonValidationErrorFor('file');
        $this->fire(['file' => null])->assertJsonValidationErrorFor('file');
    }

    /** @test */
    public function last_modified_is_a_required_filed()
    {
        $this->fire(['last_modified' => null])->assertJsonValidationErrorFor('last_modified');
        $this->fire(['last_modified' => 'not-a-timestamp'])->assertJsonValidationErrorFor('last_modified');
        $this->fire(['last_modified' => 0])->assertJsonValidationErrorFor('last_modified');
        $this->fire(['last_modified' => -300])->assertJsonValidationErrorFor('last_modified');
        $this->fire(['last_modified' => '1658994096589'])->assertJsonMissingValidationErrors('last_modified');
        $this->fire(['last_modified' => 1658994096589])->assertJsonMissingValidationErrors('last_modified');
    }

    /** @test */
    public function it_will_get_404_if_morph_model_is_not_found()
    {
        $this->admin()->postJson('/api/files', [
            'fileable_id' => 100,
            'fileable_type' => 'not a model',
        ])->assertStatus(404);
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

        $this->upload($video, 'avatar.mp4', null, $lastModified = now()->subDays(3))->assertSuccessful();

        $file = File::first();
        $this->assertNotNull($file);
        $this->assertEquals($video->id, $file->fileable_id);
        $this->assertEquals('App\Models\Video', $file->fileable_type);
        $this->assertEquals('avatar.mp4', $file->getRawOriginal('url'));
        $this->assertEquals($lastModified->format('y-m-d'), $file->last_modified->format('y-m-d'));
    }

    public function fire($overrites = [])
    {
        $file = UploadedFile::fake()->image('avatar.jpg');
        $model = create(Video::class);
        $payload = array_merge([
            'fileable_id' => $model->getKey(),
            'fileable_type' => class_basename($model),
            'file' => $file,
            'last_modified' => time(),
        ], $overrites);

        return $this->admin()->postJson('/api/files', $payload);
    }
}
