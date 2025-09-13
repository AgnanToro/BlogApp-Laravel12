<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentApprovalController extends Controller
{
    // Get pending comments
    public function pending()
    {
        $comments = Comment::with(['post:id,judul', 'approvedBy:id,name'])
            ->pending()
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return response()->json($comments);
    }

    // Approve comment
    public function approve(Comment $comment)
    {
        $comment->update([
            'approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil disetujui',
            'comment' => $comment->load(['post:id,judul', 'approvedBy:id,name'])
        ]);
    }

    // Reject/Delete comment
    public function reject(Comment $comment)
    {
        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Komentar berhasil ditolak dan dihapus'
        ]);
    }

    // Bulk approve
    public function bulkApprove(Request $request)
    {
        $request->validate([
            'comment_ids' => 'required|array',
            'comment_ids.*' => 'exists:comments,id'
        ]);

        Comment::whereIn('id', $request->comment_ids)
            ->update([
                'approved' => true,
                'approved_at' => now(),
                'approved_by' => Auth::id(),
            ]);

        return response()->json([
            'success' => true,
            'message' => count($request->comment_ids) . ' komentar berhasil disetujui'
        ]);
    }
}
