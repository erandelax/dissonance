<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * App\Models\Setting
 *
 * @property string $id
 * @property string|null $project_id
 * @property string $type
 * @property array $data
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Config newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Config query()
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Config whereUpdatedAt($value)
 * @mixin \Eloquent
 */
final class Config extends Model
{
    use HasFactory, HasUUIDKey;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    protected $casts = [
        'data' => 'array',
    ];

    public function get(string $path, mixed $default = null): mixed
    {
        $arr = $this->data ?? [];
        return Arr::get($arr, $path) ?? $default;
    }

    public function set(string $path, mixed $value): self
    {
        $arr = $this->data ?? [];
        Arr::set($arr, $path, $value);
        $this->data = $arr;
        return $this;
    }
}
