<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'url',
        'caption',
        'is_primary',
        'sort_order',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}

