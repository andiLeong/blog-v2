<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function show(Gallery $gallery, Request $request)
    {
        return File::query()
            ->withRelation($gallery->id, $gallery::class)
            ->when(
                $request->has('oldest'),
                fn($query) => $query->oldest('last_modified'),
                fn($query) => $query->latest('last_modified'),
            )
            ->latest('last_modified')
            ->paginate(10);
    }
}
