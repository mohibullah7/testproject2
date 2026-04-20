<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'stripe_session_id',
        'status',
        'quantity',
        'total_amount',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'total_amount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    
}