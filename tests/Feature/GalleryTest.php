<?php

namespace Tests\Feature;


use App\Models\File;
use App\Models\Gallery;
use Tests\TestCase;

class GalleryTest extends testcase
{
    /** @test */
    public function it_can_get_a_list_file_belongs_to_a_gallery()
    {
        $gallery = Gallery::factory()->create();
        $file = File::factory()->create([
            'fileable_id' => $gallery->id,
            'fileable_type' => class_basename(Gallery::class),
        ]);

        $response = $this->get("/api/gallaery/{$gallery->id}");
        $response->assertStatus(200);
    }
}