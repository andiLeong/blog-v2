<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

class SearchPostController extends Controller
{
    public function index()
    {
        $key = request('key');
        return Post::query()
            ->where('title', 'like', '%' . $key . '%')
            ->orWhere(fn(Builder $query) => $query->where('body', 'like', '%' . $key . '%'))
            ->get();

    }
}
