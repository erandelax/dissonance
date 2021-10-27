<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Upload
 *
 * @property int $id
 * @property string $disk
 * @property string $mime
 * @property string $name
 * @property int $size
 * @property string $path
 * @property string|null $user_id
 * @property string|null $project_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload query()
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereDisk($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereMime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereProjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereSize($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Upload whereUserId($value)
 * @mixin \Eloquent
 * @property-read string $preview_url
 * @property-read string $url
 */
final class Upload extends Model
{
    use HasFactory, HasUUIDKey;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function getUrlAttribute(): string
    {
        $url = Storage::disk($this->disk)->url($this->path);
        if (str_starts_with($url, config('app.url'))) {
            return substr($url, strlen(config('app.url')));
        }
        return $url;
    }

    public static function boot(): void
    {
        parent::boot();

        self::deleted(function (self $model) {
            if (
                Storage::disk($model->disk)->exists($model->path)
                && Storage::disk($model->disk)->size($model->path) === $model->size
            ) {
                Storage::disk($model->disk)->delete($model->path);
            }
        });
    }

    public function isImage(): bool
    {
        return str_starts_with(strtolower($this->mime), 'image/');
    }

    public function getPreviewUrlAttribute(): string
    {
        return $this->isImage()
            ? $this->url
            : scoped_route('svg', [
                'value' => '[.' . strtoupper(File::extension($this->name)) . ']'
            ]);
    }
}
