<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\FileAttributes;
use Illuminate\Contracts\Filesystem\Filesystem;

class FileController extends Controller
{
    public function store(Filesystem $fileManager)
    {
        $data = request()->validate([
            'file' => 'required|file',
            'last_modified' => 'required',
        ]);

        $file = $data['file'];
        $path = $fileManager->putFileAs('test', $file,  $file->getClientOriginalName() ,'public');
        if (! $path){
           abort(502, 'Fail to upload the file');
        }

        $file->setUploadedPath($path);
        $attribute = (new FileAttributes($file, $data['last_modified']))->toArray();
        return File::create($attribute);
    }

}
