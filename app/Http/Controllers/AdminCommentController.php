<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCommentController extends Controller
{
    /**
     * Display a listing of the comments for admin approval.
     */
    public function index()
    {
        
        return view('admin.comments.vue-index');
    }

    /**
     * Approve a comment.
     */
    public function approve(Comment $comment)
    {
        $comment->update([
            'approved' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        
        return redirect()->back()->with('success', 'Komentar berhasil disetujui.');
    }

    /**
     * Remove the specified comment from storage.
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        
        // Check if request is AJAX
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Komentar berhasil ditolak dan dihapus.'
            ]);
        }
        
        return redirect()->back()->with('success', 'Komentar berhasil ditolak dan dihapus.');
    }
}
