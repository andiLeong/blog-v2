<?php

namespace Tests\Feature;


use App\Models\File;
use App\Models\Gallery;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileUploadTest extends testcase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_will_get_403_if_morph_model_is_not_found()
    {
        $this->post('/api/files',[
            'id' => 100,
            'model' => 'notamodel',
        ])->assertStatus(403);
    }


    /** @test */
    public function it_can_upload_a_gallery_photo()
    {
        $gallery = Gallery::factory()->create();
        $file = UploadedFile::fake()->image('avatar.jpeg');

        $this->assertEquals(0,File::count());

        $response = $this->post('/api/files',[
            'fileable_id' => $gallery->id,
            'fileable_type' => 'gallery',
            'file' => $file,
            'last_modified' => now()->subDays(20)->timestamp * 1000,
        ]);
//        dd($response->getContent());

        $this->assertEquals(1,File::count());

    }
}
