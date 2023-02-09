<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;

class EnsureFileMorphModelExisted
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            File::setSourceModel($request->fileable_type, $request->fileable_id);
        } catch (\Exception $e) {
            abort(404, $e->getMessage());
        }

        return $next($request);
    }
}
