<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PenulisController extends Controller
{
    private function checkPenulisAuth()
    {
        if (!Auth::check() || Auth::user()->role !== 'penulis') {
            return redirect()->route('admin.login')->with('error', 'Akses ditolak. Silakan login sebagai penulis.');
        }
        return null;
    }
    
    // Dashboard Penulis
    public function dashboard()
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        $user = Auth::user();
        
        $totalPosts = Post::where('user_id', $user->id)->count();
        $draftPosts = 0; // Sementara tidak ada status draft
        
        // Total komentar di semua post penulis  
        $totalComments = 0; // Sementara
        
        // Post terbaru penulis
        $recentPosts = Post::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('penulis.dashboard', compact(
            'totalPosts', 
            'draftPosts',
            'totalComments', 
            'recentPosts'
        ));
    }

    // Profile Penulis
    public function profile()
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        $user = Auth::user();
        return view('penulis.profile', compact('user'));
    }

    // Update Profile Penulis
    public function updateProfile(Request $request)
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        $user = User::findOrFail(Auth::id());
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        
        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        
        $user->save();

        return redirect()->route('penulis.profile')->with('success', 'Profile berhasil diperbarui!');
    }

    // Daftar Post Penulis
    public function posts()
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        $posts = Post::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('penulis.posts.index', compact('posts'));
    }

    // Form Tambah Post
    public function createPost()
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        return view('penulis.posts.create');
    }

    // Simpan Post Baru
    public function storePost(Request $request)
    {
        $authCheck = $this->checkPenulisAuth();
        if ($authCheck) return $authCheck;
        
        $validated = $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['user_id'] = Auth::id();

        // Handle image upload
        if ($request->hasFile('foto')) {
            $imagePath = $request->file('foto')->store('posts', 'public');
            $validated['foto'] = $imagePath;
        }

        Post::create($validated);

        return redirect()->route('penulis.posts')->with('success', 'Post berhasil dibuat!');
    }

    // Form Edit Post
    public function editPost(Post $post)
    {
        // Pastikan penulis hanya bisa edit post sendiri
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('penulis.posts')->with('error', 'Anda tidak memiliki akses untuk mengedit post ini.');
        }

        return view('penulis.posts.edit', compact('post'));
    }

    // Update Post
    public function updatePost(Request $request, Post $post)
    {
        // Pastikan penulis hanya bisa update post sendiri
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('penulis.posts')->with('error', 'Anda tidak memiliki akses untuk mengedit post ini.');
        }

        $validated = $request->validate([
            'judul' => 'required|max:255',
            'konten' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        // Handle foto upload
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($post->foto) {
                Storage::disk('public')->delete($post->foto);
            }
            
            $fotoPath = $request->file('foto')->store('posts', 'public');
            $validated['foto'] = $fotoPath;
        }

        $post->update($validated);

        return redirect()->route('penulis.posts')->with('success', 'Post berhasil diupdate!');
    }

    // Hapus Post
    public function destroyPost(Post $post)
    {
        // Pastikan penulis hanya bisa hapus post sendiri
        if ($post->user_id !== Auth::id()) {
            return redirect()->route('penulis.posts')->with('error', 'Anda tidak memiliki akses untuk menghapus post ini.');
        }

        // Hapus image jika ada
        if ($post->image) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();

        return redirect()->route('penulis.posts')->with('success', 'Post berhasil dihapus!');
    }
}
