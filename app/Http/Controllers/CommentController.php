<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'nama_komentator' => 'required|string|max:255',
            'isi_komentar' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $post->id,
            'nama_komentator' => $request->nama_komentator,
            'isi_komentar' => $request->isi_komentar,
            'approved' => false, // Default false - butuh persetujuan admin
        ]);

        // Return JSON response for AJAX requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil dikirim dan menunggu persetujuan admin.',
                'comment' => $comment
            ]);
        }

        return redirect()->route('posts.show', $post)->with('success', 'Komentar berhasil dikirim dan menunggu persetujuan admin.');
    }
}