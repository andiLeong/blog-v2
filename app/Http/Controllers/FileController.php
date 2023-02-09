<?php

namespace App\Http\Controllers;

use App\Jobs\UploadFile;
use App\Models\FileAttributes;

class FileController extends Controller
{
    public function store()
    {
        $data = request()->validate([
            'file' => 'required|file',
            'last_modified' => 'required',
        ]);

        $path = $data['file']->store('files', ['disk' => 'local']);
        $path = config('filesystems.disks.local.root') . DIRECTORY_SEPARATOR . $path;

        $attributes = FileAttributes::make($data['file'], $data['last_modified']);
        UploadFile::dispatch($path, $attributes);

        return ['payload' => $attributes];
    }

}
