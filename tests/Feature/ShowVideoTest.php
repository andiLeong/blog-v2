<?php

namespace Tests\Feature;

use App\Models\Video;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\FileCanBeUploaded;
use Tests\TestCase;

class ShowVideoTest extends TestCase
{
    use LazilyRefreshDatabase;
    use FileCanBeUploaded;

    /** @test */
    public function it_can_show_all_video_files()
    {
        $video = Video::factory()->create();
        for ($x = 1; $x <= 3; $x++) {
            $this->upload($video, Str::random(5) . '.mov');
        }

        tap($this->get("/api/video/$video->id"),
            fn($response) => $this->assertNotEmpty($response->json()['data'])
        )->assertStatus(200);
    }
}
