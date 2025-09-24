<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\CommentController;

// CSRF Cookie route for Sanctum
Route::get('/sanctum/csrf-cookie', function () {
    return response()->json(['message' => 'CSRF cookie set']);
});

// Authentication routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/auth/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/auth/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::get('/reset-password/{token}', function ($token) {
    // Jika pakai SPA (Vue), redirect ke frontend
    $email = request('email');
    return redirect("http://127.0.0.1:8000/reset-password/$token?email=$email");
    })->name('password.reset');

// Public routes
Route::get('/posts', [PostController::class, 'index']);
Route::get('/posts/{post}', [PostController::class, 'show']);
Route::get('/posts/{post}/comments', [CommentController::class, 'index']);
Route::get('/search', [PostController::class, 'search']);


// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User info
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [App\Http\Controllers\Api\AuthController::class, 'updateProfile']);
    // Comments
    Route::post('/posts/{post}/comments', [CommentController::class, 'store']);
    Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);
    // Posts (for authenticated users)
    Route::post('/posts', [PostController::class, 'store']);
    Route::get('/my-posts', [PostController::class, 'myPosts']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
   // Route::delete('/posts/{post}', [PostController::class, 'destroy']);
     Route::delete('/my-posts/{post}', [PostController::class, 'destroy']);
 

    // Admin only routes
    Route::middleware('isAdmin')->group(function () {
        // Dashboard
        Route::get('/admin/dashboard', [App\Http\Controllers\Api\AdminController::class, 'dashboard']);
        // Posts management
        Route::get('/admin/posts', [PostController::class, 'adminIndex']);
        Route::post('/admin/posts', [PostController::class, 'store']);
        Route::get('/admin/posts/{post}', [PostController::class, 'show']);
        Route::put('/admin/posts/{post}', [PostController::class, 'update']);
        Route::delete('/admin/posts/{post}', [PostController::class, 'destroy']);
        // Users management
        Route::get('/admin/users', [App\Http\Controllers\Api\UserController::class, 'index']);
        Route::post('/admin/users', [App\Http\Controllers\Api\UserController::class, 'store']);
        Route::get('/admin/users/{user}', [App\Http\Controllers\Api\UserController::class, 'show']);
        Route::put('/admin/users/{user}', [App\Http\Controllers\Api\UserController::class, 'update']);
        Route::delete('/admin/users/{user}', [App\Http\Controllers\Api\UserController::class, 'destroy']);
        // Comments management
        Route::get('/admin/comments', [CommentController::class, 'getAllComments']);
        Route::patch('/comments/{comment}/approve', [CommentController::class, 'approve']);
        Route::patch('/comments/{comment}/reject', [CommentController::class, 'reject']);
        Route::delete('/admin/comments/{comment}', [CommentController::class, 'destroy']);
    });
});