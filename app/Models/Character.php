<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Character
 *
 * @property int $id
 * @property string|null $user_id
 * @property string|null $player_name
 * @property int|null $player_id
 * @property string $name
 * @property int $status
 * @property string $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Character newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Character newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Character query()
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character wherePlayerName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Character whereUserId($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 */
class Character extends Model
{
    use HasFactory, HasUUIDKey;

    protected $guarded = ['id'];

    protected $casts = [
        'data' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
