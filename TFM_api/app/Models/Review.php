<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     schema="Review",
 *     title="Review",
 *     description="Review model",
 *     type="object",
 *     required={"id", "user_id", "service_id", "rating"},
 *     @OA\Property(property="id", type="integer", example=5),
 *     @OA\Property(property="user_id", type="integer", example=2),
 *     @OA\Property(property="service_id", type="integer", example=10),
 *     @OA\Property(property="rating", type="integer", minimum=1, maximum=5, example=5),
 *     @OA\Property(property="comment", type="string", example="Servicio excelente"),
 *     @OA\Property(property="user", ref="#/components/schemas/UserBasic"),
 *     @OA\Property(property="service", ref="#/components/schemas/ServiceBasic")
 * )
 */
class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'service_id',
        'rating',
        'comment',
    ];

    protected $casts = [
        'rating' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id');
    }
}
