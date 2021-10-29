<?php

namespace App\Models;

use App\Concerns\HasRevisions;
use App\Concerns\HasUUIDKey;
use App\Services\Markdown\MarkupRender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * App\Models\Page
 *
 * @property string $id
 * @property string $locale
 * @property string $slug
 * @property string $title
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $html
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\PageRevision[] $revisions
 * @property-read int|null $revisions_count
 * @method static \Illuminate\Database\Eloquent\Builder|Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $project_id
 * @property-read \App\Models\Project|null $project
 * @method static \Illuminate\Database\Eloquent\Builder|Page whereProjectId($value)
 */
final class Page extends Model
{
    use HasFactory, HasUUIDKey, HasRevisions;

    protected $guarded = ['id'];

    public function getRevisionModelClass(): string
    {
        return PageRevision::class;
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function getHtmlAttribute(): string
    {
        return app()->make(MarkupRender::class)->toHtml($this->content ?? '');
    }

    public function setSlugAttribute(string|null $slug): void
    {
        $this->attributes['slug'] = (string)$slug;
    }
}
