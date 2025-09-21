<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CommentController extends Controller
{
    public function index(Post $post): JsonResponse
    {
        $comments = Comment::where('post_id', $post->id)
            ->where('approved', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $comments
        ]);
    }

    public function store(Request $request, Post $post): JsonResponse
    {
        $request->validate([
            'nama_komentator' => 'required|string|max:255',
            'isi_komentar' => 'required|string|max:1000',
        ]);

        $comment = Comment::create([
            'nama_komentator' => $request->nama_komentator,
            'isi_komentar' => $request->isi_komentar,
            'post_id' => $post->id,
            'approved' => false, // Comments need approval by default
        ]);

        return response()->json([
            'success' => true,
            'data' => $comment
        ], 201);
    }

    public function approve(Request $request, Comment $comment): JsonResponse
    {
        // Only admin can approve comments
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'approved' => true,
            'approved_at' => now(),
            'approved_by' => $request->user()->id
        ]);
        return response()->json([
            'message' => 'Comment approved successfully',
            'comment' => $comment->fresh()
        ]);
    }

    public function reject(Request $request, Comment $comment): JsonResponse
    {
        // Only admin can reject comments
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'approved' => false,
            'approved_at' => null,
            'approved_by' => null
        ]);
        return response()->json([
            'message' => 'Comment rejected successfully',
            'comment' => $comment->fresh()
        ]);
    }

    public function destroy(Request $request, Comment $comment): JsonResponse
    {
        // Only admin can delete comments in admin panel
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil dihapus'
        ]);
    }

    // Get all comments for admin
    public function getAllComments(Request $request): JsonResponse
    {
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comments = Comment::with(['post'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => $comments->items(),
            'meta' => [
                'current_page' => $comments->currentPage(),
                'last_page' => $comments->lastPage(),
                'per_page' => $comments->perPage(),
                'total' => $comments->total()
            ]
        ]);
    }
}