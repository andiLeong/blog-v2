<?php

namespace App\Http\Controllers;

use App\Models\File;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;

class FileController extends Controller
{
    public function store(Filesystem $fileManager)
    {
        $data = request()->validate([
            'file' => 'required|file|image',
            'last_modified' => 'required',
        ]);

        $file = $data['file'];
        unset($data['file']);
        $path = $fileManager->putFileAs('junsing', $file,  $file->getClientOriginalName() ,'public');
        if (! $path){
           abort(502, 'Fail to upload the file');
        }

        $data = array_merge($data,[
            'name' => $file->getClientOriginalName(),
            'url' => $path,
            'type' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
            'last_modified' => Carbon::createFromTimestamp($data['last_modified'] / 1000),
        ]);

        return File::create($data);
    }

}
