<?php


use App\Models\File;
use App\Models\Gallery;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;


class GalleryTest extends testcase
{

    use LazilyRefreshDatabase;

    /** @test */
    public function it_can_load_the_files_relationship()
    {
        $gallery = Gallery::factory()->create();
        $file = File::factory()->create([
            'fileable_id' => $gallery->id,
            'fileable_type' => Model::getActualClassNameForMorph(Gallery::class),
        ]);
        $this->assertEquals($file->id,$gallery->files[0]->id);
    }

}
