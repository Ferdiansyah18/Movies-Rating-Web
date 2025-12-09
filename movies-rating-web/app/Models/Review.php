<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'item_id', 'item_type', 'title', 'rating', 'comment'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // App/Models/Review.php

public function likes()
{
    return $this->belongsToMany(User::class, 'review_likes');
}

// Helper untuk mengecek apakah user yang sedang login sudah like
public function isLikedBy(User $user)
{
    return $this->likes->contains($user);
}
}

