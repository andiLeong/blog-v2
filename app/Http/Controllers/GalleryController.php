<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function show(Gallery $gallery)
    {
        return File::withRelation($gallery->id,$gallery::class)->paginate(10);
    }
}
