<?php

namespace App\Models;

use App\Concerns\HasUUIDKey;
use App\Services\Wiki\MarkupRender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\WikiPage
 *
 * @property string $guid
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property string $refs
 * @property-read string $html
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage query()
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereGuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereRefs($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|WikiPage whereLocale($value)
 */
class WikiPage extends Model
{
    use HasFactory, HasUUIDKey;

    protected $primaryKey = 'guid';

    protected $guarded = ['guid'];

    public function getHtmlAttribute(): string
    {
        return app()->make(MarkupRender::class)->toHtml($this->content ?? '');
    }
}
