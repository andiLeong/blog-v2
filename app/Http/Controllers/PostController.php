<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    public function index()
    {
        $page = request('perPage') ?? 5;
        return Post::with('tags')->select(['id', 'slug', 'title', 'body'])->latest()->paginate($page);
    }

    public function store(Post $post)
    {
        $data = request()->validate([
            'title' => 'required|unique:posts|max:255|string',
            'body' => 'required|string',
            'tags' => 'required|array',
        ]);
        return $post->store($data);
    }


    public function update(Post $post)
    {
        $data = request()->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('posts')->ignore($post->id),
            ],
            'body' => 'required|string',
            'tags' => 'required|array',
        ]);

        return $post->patch($data);
    }


    public function show(Post $post)
    {
        $post->load('tags');
        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return ['message' => 'success'];
    }
}
