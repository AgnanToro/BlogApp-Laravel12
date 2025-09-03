<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = ['judul', 'konten', 'tanggal_post', 'foto'];

    protected $casts = [
        'tanggal_post' => 'datetime',
    ];

    public function comments(): HasMany
    {
    return $this->hasMany(Comment::class)->latest();
    }
}
