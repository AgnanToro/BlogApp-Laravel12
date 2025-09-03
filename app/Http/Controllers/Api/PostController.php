<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Search posts by title or content
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json(['posts' => []]);
        }
        
        $posts = Post::where('judul', 'LIKE', "%{$query}%")
                    ->orWhere('konten', 'LIKE', "%{$query}%")
                    ->withCount('comments')
                    ->orderBy('tanggal_post', 'desc')
                    ->limit(10)
                    ->get(['id', 'judul', 'konten', 'foto', 'tanggal_post']);
        
        return response()->json(['posts' => $posts]);
    }
}
