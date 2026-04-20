<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{

    use HasFactory;

     protected $fillable = [
        'name',
        'price',
        'detail',
        'image'  // Make sure 'image' is in fillable!
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::url($this->image) : null;
    }


}
