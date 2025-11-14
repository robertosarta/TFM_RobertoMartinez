<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wedding extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'name',
        'wedding_date',
        'location',
        'notes',
        'budget',
        'guest_count',
        'status',
    ];

    protected $casts = [
        'wedding_date' => 'date',
        'budget' => 'decimal:2',
        'guest_count' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'wedding_service')
            ->withPivot(['price', 'quantity', 'notes', 'status'])
            ->withTimestamps();
    }
}

