<?php

namespace App\Http\Middleware;

use Closure;

class EnsureFileMorphModel
{

    public function handle(Request $request, Closure $next)
    {
        $model = "\App\Models\{$request->model}" ;
        $id = $request->id;

        if(!class_exists($model)){
            abort(403);
        }
        $model::findOrFail($id);

        $request->fileable_id = $id;
        $request->fileable_type = $model;
        return $next($request);
    }


}

