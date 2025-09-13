<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CommentApprovalController;
use App\Http\Controllers\Api\TestCommentApprovalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Comment approval routes (protected by web auth middleware)
Route::middleware(['web', 'auth', 'admin'])->prefix('admin/comments')->group(function () {
    Route::get('/pending', [CommentApprovalController::class, 'pending']);
    Route::post('/{comment}/approve', [CommentApprovalController::class, 'approve']);
    Route::delete('/{comment}/reject', [CommentApprovalController::class, 'reject']);
    Route::post('/bulk-approve', [CommentApprovalController::class, 'bulkApprove']);
});

// Test routes without auth (for debugging)
Route::prefix('test-comments')->group(function () {
    Route::get('/pending', [TestCommentApprovalController::class, 'pending']);
    Route::post('/{comment}/approve', [TestCommentApprovalController::class, 'approve']);
    Route::delete('/{comment}/reject', [TestCommentApprovalController::class, 'reject']);
});