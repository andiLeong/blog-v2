<?php

namespace App\Http\Controllers;

use App\Models\File;

class FileController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'file' => 'required|file|image',
            'morph_id' => 'required',
            'lastModified' => 'required',
            'fileable_id' => 'required',
            'fileable_type' => 'required',
        ]);

        return File::create($data);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return ['message' => 'success'];
    }
}
