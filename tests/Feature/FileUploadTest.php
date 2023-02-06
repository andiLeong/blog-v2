<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\Gallery;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Mockery\MockInterface;
use Tests\TestCase;

class FileUploadTest extends testcase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_will_get_404_if_morph_model_is_not_found()
    {
        $this->be(admin());
        $this->postJson('/api/files', [
            'fileable_id' => 100,
            'fileable_type' => 'not a model',
        ])->assertStatus(404);
    }

    /** @test */
    public function it_response_with_502_if_upload_fails()
    {
        $this->be(admin());
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            $mock->shouldReceive('putFileAs')->once()->andReturn(false);
        });

        $this->postJson('/api/files', [
            'fileable_id' => Gallery::factory()->create()->id,
            'fileable_type' => 'gallery',
            'file' => UploadedFile::fake()->image('avatar.jpeg'),
            'last_modified' => now()->subDays(20)->timestamp * 1000,
        ])->assertStatus(502);
    }

    /** @test */
    public function it_can_upload_a_gallery_photo()
    {
        $this->be(admin());
        $gallery = Gallery::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpeg');

        $this->assertEquals(0, File::count());
        $this->mock(Filesystem::class, function (MockInterface $mock) {
            $mock->shouldReceive('putFileAs')->once()->andReturn('avatar.jpeg');
        });

        $response = $this->postJson('/api/files', [
            'fileable_id' => $gallery->id,
            'fileable_type' => 'gallery',
            'file' => $file,
            'last_modified' => now()->subDays(20)->timestamp * 1000,
        ]);
        $file = File::first();

        $response->assertSuccessful();
        $this->assertNotNull($file);
        $this->assertEquals($gallery->id, $file->fileable_id);
        $this->assertEquals('App\Models\Gallery', $file->fileable_type);
    }
}
