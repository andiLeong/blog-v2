<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Validation\Rule;

class PostController extends Controller
{

    public function index()
    {
        $page = request('perPage') ?? 5;
        return Post::latest()->paginate($page);
    }

    public function store()
    {
        $data = request()->validate([
            'title' => 'required|unique:posts',
            'body' => 'required',
        ]);

        return Post::create($data + ['user_id' => auth()->id()] );
    }


    public function update(Post $post)
    {
        $data = request()->validate([
            'title' => [
                'required',
                Rule::unique('posts')->ignore($post->title),],
            'body' => 'required'
        ]);
        return tap($post)->update($data);
    }


    public function show(Post $post)
    {
        return $post;
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return ['message' => 'success'];
    }
}
