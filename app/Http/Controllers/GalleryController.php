<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{

    public function show(Gallery $gallery)
    {
        $page = request('perPage') ?? 5;
        return $gallery->files()->orderBy('pinned')->paginate($page);
    }

}
