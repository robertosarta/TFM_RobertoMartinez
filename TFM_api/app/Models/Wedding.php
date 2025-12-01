<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Wedding",
 *     title="Wedding",
 *     description="Wedding model",
 *     type="object",
 *     required={"id", "user_id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="name", type="string", example="Boda de Marta y Luis"),
 *     @OA\Property(property="wedding_date", type="string", format="date", example="2025-06-15"),
 *     @OA\Property(property="location", type="string", example="Madrid"),
 *     @OA\Property(property="notes", type="string", example="Ceremonia al aire libre"),
 *     @OA\Property(property="budget", type="string", example="15000.00"),
 *     @OA\Property(property="guest_count", type="integer", example=120),
 *     @OA\Property(property="status", type="string", example="gestionando"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserBasic"),
 *     @OA\Property(
 *         property="services",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/WeddingService")
 *     )
 * )
 */
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
        'wedding_date' => 'date:Y-m-d',
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
