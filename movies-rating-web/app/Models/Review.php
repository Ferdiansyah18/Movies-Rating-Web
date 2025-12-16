<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    // UPDATE BAGIAN INI
    protected $fillable = [
        'user_id', 
        'item_id', 
        'item_type', 
        'title', 
        'rating', 
        'comment',
        
        // --- TAMBAHAN PENTING (Data Snapshot) ---
        'media_title', 
        'media_poster', 
        'media_year',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'review_likes');
    }

    public function isLikedBy(User $user)
    {
        return $this->likes->contains($user);
    }
}