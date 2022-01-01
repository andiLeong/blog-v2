<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         \App\Models\User::factory(10)->create();
        \App\Models\Post::factory(50)->create();
        $gallery = Gallery::factory()->create();
        \App\Models\File::factory(50)->create([
            'fileable_id' => $gallery->id,
            'fileable_type' => Model::getActualClassNameForMorph(Gallery::class),
        ]);
    }
}
