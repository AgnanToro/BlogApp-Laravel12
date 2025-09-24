<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        try {
            $stats = [
                'totalPosts' => Post::count(),
                'totalUsers' => User::count(),
                'totalComments' => Comment::count(),
                'pendingComments' => Comment::where('approved', false)->count(),
                'todayPosts' => Post::whereDate('created_at', today())->count(),
                'todayUsers' => User::whereDate('created_at', today())->count(),
            ];

            $recentPosts = Post::with(['user'])
                ->orderBy('tanggal_post', 'desc')
                ->take(5)
                ->get()
                ->map(function ($post) {
                    return [
                        'id' => $post->id,
                        'judul' => $post->judul,
                        'konten' => $post->konten,
                        'tanggal_post' => $post->tanggal_post,
                        'user' => $post->user ? $post->user->name : 'Unknown',
                        'comments_count' => $post->comments()->count()
                    ];
                });

            return response()->json([
                'stats' => $stats,
                'recentPosts' => $recentPosts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to fetch dashboard data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}