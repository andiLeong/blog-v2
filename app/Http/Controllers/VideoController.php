<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Video;

class VideoController extends Controller
{
    //
    public function show(Video $video)
    {
        return File::query()
            ->withRelation($video->id, $video::class)
            ->latest('last_modified')
            ->paginate(10);

    }
}
