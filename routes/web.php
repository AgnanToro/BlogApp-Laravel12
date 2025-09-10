<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PenulisController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\UserController as AdminUserController;


// Forgot Password (Admin)
Route::get('/admin/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('admin.password.request');
Route::post('/admin/forgot-password', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('admin.password.email');

// Reset Password (untuk link di email)
Route::get('/admin/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token, 'email' => request('email')]);
})->name('password.reset');

// Proses reset password (POST)
use App\Http\Controllers\Auth\ResetPasswordController;
Route::post('/admin/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');

// Public routes
Route::get('/', [PostController::class, 'index'])->name('posts.index');
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
Route::post('/posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');

// Authentication routes
Route::get('/admin/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [LoginController::class, 'login'])->name('login');
Route::post('/admin/logout', [LoginController::class, 'logout'])->name('admin.logout');

// Admin routes (protected by auth and admin middleware)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('posts', AdminPostController::class);
    Route::resource('users', AdminUserController::class);
    Route::delete('/comments/{comment}', [AdminCommentController::class, 'destroy'])->name('comments.destroy');
});

// Penulis routes (protected by auth and penulis middleware)
Route::middleware(['auth', 'penulis'])->prefix('penulis')->name('penulis.')->group(function () {
    Route::get('/dashboard', [PenulisController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [PenulisController::class, 'profile'])->name('profile');
    Route::put('/profile', [PenulisController::class, 'updateProfile'])->name('profile.update');
    Route::get('/posts', [PenulisController::class, 'posts'])->name('posts');
    Route::get('/posts/create', [PenulisController::class, 'createPost'])->name('posts.create');
    Route::post('/posts', [PenulisController::class, 'storePost'])->name('posts.store');
    Route::get('/posts/{post}/edit', [PenulisController::class, 'editPost'])->name('posts.edit');
    Route::put('/posts/{post}', [PenulisController::class, 'updatePost'])->name('posts.update');
    Route::delete('/posts/{post}', [PenulisController::class, 'destroyPost'])->name('posts.destroy');
});