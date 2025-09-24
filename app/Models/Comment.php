<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'post_id', 
        'nama_komentator', 
        'isi_komentar',
        'approved',
        'approved_at',
        'approved_by'
    ];

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scope untuk komentar yang sudah diapprove
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }

    // Scope untuk komentar pending
    public function scopePending($query)
    {
        return $query->where('approved', false);
    }
}
