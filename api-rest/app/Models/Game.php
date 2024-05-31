<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @OA\Schema(
 *     schema="Game",
 *     @OA\Property(property="dice_1", type="integer", example=1),
 *     @OA\Property(property="dice_2", type="integer", example=2),
 * )
 */
class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'dice_1',
        'dice_2',
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
}
