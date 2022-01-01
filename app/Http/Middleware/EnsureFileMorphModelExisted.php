<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureFileMorphModelExisted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $model = ucfirst(strtolower($request->fileable_type));
        $model = "\\App\\Models\\$model";
        $id = $request->fileable_id;

        if(!class_exists($model)){
            abort(403,'Model isn\'t found ');
        }
        $model::findOrFail($id);

        return $next($request);
    }
}
