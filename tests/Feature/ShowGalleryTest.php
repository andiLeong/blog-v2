<?php

namespace Tests\Feature;

use App\Models\Gallery;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Tests\TestCase;

class ShowGalleryTest extends TestCase
{
    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_show_all_gallery_files()
    {
        $this->be(admin());

        $gallery = Gallery::factory()->create();
        for ($x = 1; $x <= 3; $x++) {
            $this->uploadFile($gallery->id, Str::random(5));
        }

        $response = $this->get("/api/gallery/$gallery->id");
        $this->assertNotEmpty($response->json()['data']);
        $response->assertStatus(200);
    }

    /** @test */
    public function by_default_it_sorts_gallery_by_latest_modified_date()
    {
        $this->be(admin());
        $id = Gallery::factory()->create()->id;
        $this->uploadFile($id, Str::random(5), today()->subDay());
        $this->uploadFile($id, 'latest', today());

        $files = $this->get("/api/gallery/$id")->json('data');

        $this->assertEquals('latest.jpeg', $files[0]['name']);
    }

    /** @test */
    public function it_sorts_gallery_by_dynamic_query_string()
    {
        $this->be(admin());
        $id = Gallery::factory()->create()->id;
        $this->uploadFile($id, $name = Str::random(5), today()->subDay());
        $this->uploadFile($id, 'latest', today());

        $files = $this->get("/api/gallery/$id?oldest=1")->json('data');

        $this->assertEquals("latest.jpeg", $files[1]['name']);
        $this->assertEquals("$name.jpeg", $files[0]['name']);
    }

    private function uploadFile($id, $name, $lastModified = null)
    {
        if ($lastModified instanceof \DateTimeInterface) {
            $lastModified = $lastModified->getTimestamp() * 1000;
        } else {
            $lastModified = now()->subDays(20)->getTimestamp() * 1000;
        }

        $this->postJson('/api/files', [
            'fileable_id' => $id,
            'fileable_type' => 'gallery',
            'file' => UploadedFile::fake()->image("$name.jpeg"),
            'last_modified' => $lastModified,
        ]);
    }
}
