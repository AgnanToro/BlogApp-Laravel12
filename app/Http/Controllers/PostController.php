<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource for public.
     */
    public function index()
    {
        $query = Post::with('user')->orderBy('tanggal_post', 'desc');
        $keyword = request('q');
        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                $q->where('judul', 'like', "%$keyword%")
                  ->orWhere('konten', 'like', "%$keyword%")
                  ->orWhere('id', $keyword);
            });
        }
        $posts = $query->paginate(6)->withQueryString();
        $totalComments = \App\Models\Comment::approved()->count();
        return view('posts.index', compact('posts', 'totalComments', 'keyword'));
    }

    /**
     * Display the specified resource for public.
     */
    public function show(Post $post)
    {
        $post->load(['comments' => function($query) {
            $query->approved()->orderBy('created_at', 'desc');
        }, 'user']);
        return view('posts.show', compact('post'));
    }
}
