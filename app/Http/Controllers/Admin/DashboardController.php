<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function index()
    {
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $recentPosts = Post::orderBy('tanggal_post', 'desc')->take(5)->get();

        return view('admin.dashboard', compact('totalPosts', 'totalComments', 'recentPosts'));
    }
}
