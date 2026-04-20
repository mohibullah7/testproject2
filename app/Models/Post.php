<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{



protected $fillable = [
    'user_id',
    'title',
    'slug',
    'body',
    'image',
    'status',
    'published_at',
    'views'
];

   // Add casts to automatically convert to Carbon/DateTime
    protected $casts = [
        'published_at' => 'datetime',  // This converts string to Carbon object
        'views' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

// Relationship
public function user()
{
    return $this->belongsTo(User::class);
}


// Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    // Scope for draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

}
