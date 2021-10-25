<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use App\Entities\ProjectReference;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Project
 *
 * @property int $id
 * @property string|null $user_id
 * @property string|null $host
 * @property int|null $port
 * @property string|null $title
 * @property string $description
 * @property string|null $avatar
 * @property-read ProjectReference $reference
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Project newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Project query()
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereAvatar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereHost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project wherePort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Project whereUserId($value)
 * @mixin \Eloquent
 */
class Project extends Model
{
    use HasFactory, HasUUIDKey;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * @return \App\Entities\ProjectReference
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function getReferenceAttribute(): ProjectReference
    {
        return app()->make(ProjectReference::class)->setHost($this->host)->setPort($this->port);
    }

    /**
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->name ?? implode(':', array_filter([$this->host, $this->port]));
    }
}
