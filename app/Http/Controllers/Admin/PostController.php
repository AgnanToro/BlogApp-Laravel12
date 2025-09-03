<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index()
    {
    $posts = Post::orderBy('tanggal_post', 'desc')->paginate(5);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
        ]);

        $fotoPath = null;
        
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            // Simple validation
            if ($file->isValid()) {
                // Check file extension manually
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                
                if (in_array($extension, $allowedExtensions)) {
                    // Check file size (30MB = 30 * 1024 * 1024 bytes)
                    if ($file->getSize() <= 30 * 1024 * 1024) {
                        try {
                            $fotoPath = $file->store('posts', 'public');
                            Log::info('File stored successfully: ' . $fotoPath);
                        } catch (\Exception $e) {
                            Log::error('File storage failed: ' . $e->getMessage());
                            return back()->withErrors(['foto' => 'Gagal menyimpan file. Silakan coba lagi.']);
                        }
                    } else {
                        return back()->withErrors(['foto' => 'Ukuran file terlalu besar. Maksimal 30MB.']);
                    }
                } else {
                    return back()->withErrors(['foto' => 'Format file tidak didukung. Gunakan: JPG, PNG, GIF, SVG, WEBP.']);
                }
            } else {
                return back()->withErrors(['foto' => 'File upload gagal. Silakan coba lagi.']);
            }
        }

        $post = Post::create([
            'judul' => $validated['judul'],
            'konten' => $validated['konten'],
            'foto' => $fotoPath,
            'tanggal_post' => now(),
        ]);

        Log::info('Post created with foto: ' . $fotoPath);

        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil ditambahkan!');
    }

    public function edit(Post $post)
    {
        return view('admin.posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            
            if ($file->isValid()) {
                // Check file extension manually
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                
                if (in_array($extension, $allowedExtensions)) {
                    // Check file size (30MB = 30 * 1024 * 1024 bytes)
                    if ($file->getSize() <= 30 * 1024 * 1024) {
                        // Delete old photo
                        if ($post->foto) {
                            Storage::disk('public')->delete($post->foto);
                        }
                        $fotoPath = $file->store('posts', 'public');
                        $post->foto = $fotoPath;
                    } else {
                        return back()->withErrors(['foto' => 'Ukuran file terlalu besar. Maksimal 30MB.']);
                    }
                } else {
                    return back()->withErrors(['foto' => 'Format file tidak didukung. Gunakan: JPG, PNG, GIF, SVG, WEBP.']);
                }
            } else {
                return back()->withErrors(['foto' => 'File upload gagal. Silakan coba lagi.']);
            }
        }

        $post->judul = $validated['judul'];
        $post->konten = $validated['konten'];
        $post->save();

        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil diupdate!');
    }

    public function destroy(Post $post)
    {
        if ($post->foto) {
            Storage::disk('public')->delete($post->foto);
        }
        $post->delete();
        return redirect()->route('admin.posts.index')->with('success', 'Post berhasil dihapus!');
    }

    public function show(Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }
}
