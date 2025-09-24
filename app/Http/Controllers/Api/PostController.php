<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $perPage = $request->input('per_page', 6);
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Tambahkan comments_count (approved only) ke setiap post
        $posts->getCollection()->transform(function ($post) {
            $post->comments_count = $post->comments()->where('approved', true)->count();
            if ($post->foto && !str_starts_with($post->foto, 'posts/')) {
                $post->foto = 'posts/' . basename($post->foto);
            }
            return $post;
        });
        return response()->json($posts);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load('user');
        // Hitung hanya komentar yang sudah diapprove
        $post->comments_count = $post->comments()->where('approved', true)->count();
        // Pastikan path foto benar
        if ($post->foto && !str_starts_with($post->foto, 'posts/')) {
            $post->foto = 'posts/' . basename($post->foto);
        }
        return response()->json([
            'success' => true,
            'data' => $post
        ]);
    }

    public function store(Request $request): JsonResponse
    {

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal_post' => now(),
            'user_id' => $request->user()->id,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if ($file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                if (in_array($extension, $allowedExtensions)) {
                    if ($file->getSize() <= 30 * 1024 * 1024) {
                        try {
                            $path = $file->store('posts', 'public');
                            $data['foto'] = $path;
                            // Copy juga ke public/storage/posts agar bisa diakses langsung
                            $publicPath = public_path('storage/posts/' . basename($path));
                            $storagePath = storage_path('app/public/posts/' . basename($path));
                            if (file_exists($storagePath)) {
                                if (!file_exists(dirname($publicPath))) {
                                    mkdir(dirname($publicPath), 0777, true);
                                }
                                copy($storagePath, $publicPath);
                            }
                        } catch (\Exception $e) {
                            return response()->json(['message' => 'Gagal menyimpan file. Silakan coba lagi.'], 500);
                        }
                    } else {
                        return response()->json(['message' => 'Ukuran file terlalu besar. Maksimal 30MB.'], 422);
                    }
                } else {
                    return response()->json(['message' => 'Format file tidak didukung. Gunakan: JPG, PNG, GIF, SVG, WEBP.'], 422);
                }
            } else {
                return response()->json(['message' => 'File upload gagal. Silakan coba lagi.'], 422);
            }
        }

        $post = Post::create($data);
        $post->load('user');

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post): JsonResponse
    {
        // Check if user owns the post or is admin
        if ($post->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required',
        ]);

        $data = [
            'judul' => $request->judul,
            'konten' => $request->konten,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            if ($file->isValid()) {
                $extension = strtolower($file->getClientOriginalExtension());
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                if (in_array($extension, $allowedExtensions)) {
                    if ($file->getSize() <= 30 * 1024 * 1024) {
                        // Delete old image if exists
                        if ($post->foto && Storage::disk('public')->exists($post->foto)) {
                            Storage::disk('public')->delete($post->foto);
                        }
                        try {
                            $imageName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                            $imagePath = $file->storeAs('posts', $imageName, 'public');
                            // Copy juga ke public/storage/posts agar bisa diakses langsung
                            $publicPath = public_path('storage/posts/' . $imageName);
                            $storagePath = storage_path('app/public/posts/' . $imageName);
                            if (file_exists($storagePath)) {
                                if (!file_exists(dirname($publicPath))) {
                                    mkdir(dirname($publicPath), 0777, true);
                                }
                                copy($storagePath, $publicPath);
                            }
                            $data['foto'] = 'posts/' . $imageName;
                        } catch (\Exception $e) {
                            return response()->json(['message' => 'Gagal menyimpan file. Silakan coba lagi.'], 500);
                        }
                    } else {
                        return response()->json(['message' => 'Ukuran file terlalu besar. Maksimal 30MB.'], 422);
                    }
                } else {
                    return response()->json(['message' => 'Format file tidak didukung. Gunakan: JPG, PNG, GIF, SVG, WEBP.'], 422);
                }
            } else {
                return response()->json(['message' => 'File upload gagal. Silakan coba lagi.'], 422);
            }
        } else {
            // Jika tidak upload foto baru, pertahankan path foto lama
            $data['foto'] = $post->foto;
        }

        $post->update($data);
        $post->load('user');

        return response()->json($post);
    }

    public function destroy(Request $request, Post $post): JsonResponse
    {
        // Check if user owns the post or is admin
        if ($post->user_id !== $request->user()->id && !$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete image if exists
        if ($post->foto && Storage::disk('public')->exists($post->foto)) {
            Storage::disk('public')->delete($post->foto);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    // Get posts by current user (for penulis dashboard)
    public function myPosts(Request $request): JsonResponse
    {

        $perPage = $request->input('per_page', 5);
        $posts = Post::where('user_id', $request->user()->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        // Tambahkan comments_count (approved only) ke setiap post
        $posts->getCollection()->transform(function ($post) {
            $post->comments_count = $post->comments()->where('approved', true)->count();
            if ($post->foto && !str_starts_with($post->foto, 'posts/')) {
                $post->foto = 'posts/' . basename($post->foto);
            }
            return $post;
        });

        return response()->json($posts);
    }

    /**
     * Get posts for admin panel with pagination
     */
    public function adminIndex(Request $request): JsonResponse
    {
        $perPage = $request->get('per_page', 15);
        
        $posts = Post::with(['user', 'comments'])
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%")
                      ->orWhere('konten', 'like', "%{$search}%");
                });
            })
            ->orderBy('tanggal_post', 'desc')
            ->paginate($perPage);

        // Add approved comments count to each post dan pastikan path foto benar
        $posts->getCollection()->transform(function ($post) {
            $post->comments_count = $post->comments()->where('approved', true)->count();
            if ($post->foto && !str_starts_with($post->foto, 'posts/')) {
                $post->foto = 'posts/' . basename($post->foto);
            }
            return $post;
        });

        return response()->json($posts);
    }

    /**
     * Search posts by title or content
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return response()->json(['posts' => []]);
        }
        
        $posts = Post::where('judul', 'LIKE', "%{$query}%")
                    ->orWhere('konten', 'LIKE', "%{$query}%")
                    ->with('user')
                    ->orderBy('tanggal_post', 'desc')
                    ->limit(10)
                    ->get();
        // Pastikan path foto benar
        $posts->transform(function ($post) {
            if ($post->foto && !str_starts_with($post->foto, 'posts/')) {
                $post->foto = 'posts/' . basename($post->foto);
            }
            return $post;
        });
        return response()->json(['posts' => $posts]);
    }
}
