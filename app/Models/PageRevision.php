<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PageRevision
 *
 * @property string $id
 * @property string $item_id
 * @property string|null $user_id
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Page $page
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision query()
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereItemId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PageRevision whereUserId($value)
 * @mixin \Eloquent
 */
class PageRevision extends Model
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

    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class, 'page_id');
    }
}
