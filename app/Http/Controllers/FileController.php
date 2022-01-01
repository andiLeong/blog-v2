<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Gallery;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Database\Eloquent\Model;

class FileController extends Controller
{
    public function store(Filesystem $fileManager)
    {
        $data = request()->validate([
            'file' => 'required|file|image',
            'last_modified' => 'required',
            'fileable_type' => 'required',
            'fileable_id' => 'required',
        ]);

        $file = $data['file'];
        $path = $fileManager->putFileAs('junsing', $file,  $file->getClientOriginalName() ,'public');

        $data = collect($data)->merge([
            'pinned' => false,
            'name' => $file->getClientOriginalName(),
            'url' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'fileable_type' => Model::getActualClassNameForMorph("App\\Models\\". ucfirst(strtolower(request('fileable_type')))),
            'last_modified' => Carbon::createFromTimestamp($data['last_modified'] / 1000),
        ])->except('file')->all();

//        dd($data);

        return File::create($data);
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return ['message' => 'success'];
    }
}
