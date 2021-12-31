<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        return Post::all();
    }

    public function selfPosts()
    {
        return Post::where('user_id', Auth::id())
            ->join('users', 'users.id', '=', 'posts.user_id')
            ->select([
                'posts.id',
                'posts.title',
                'posts.body',
                'users.name as author',
            ])
            ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string'],
            'body' => ['required', 'string'],
        ]);

        return Post::create(array_merge($request->all(), ['user_id' => Auth::id()]));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);
        $post->update($request->all());
        return $post;
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();
        return $post;
    }
}
