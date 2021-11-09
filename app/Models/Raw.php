<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use App\Enums\RawTypeEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\Raw
 *
 * @property string $id
 * @property RawTypeEnum $type
 * @property string|null $uri
 * @property string|null $body
 * @property string|null $user_id
 * @property string|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Project|null $project
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|Raw newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Raw newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Raw query()
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereUri($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereUserId($value)
 * @mixin \Eloquent
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|Raw whereLocale($value)
 */
final class Raw extends Model
{
    use HasFactory, HasUUIDKey;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'type' => RawTypeEnum::class,
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }
}
