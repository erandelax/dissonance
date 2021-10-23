<?php

declare(strict_types=1);

namespace App\Concerns;

use App\Models\PageRevision;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

/**
 * @package App\Concerns
 * @mixin Model
 */
trait HasRevisions
{
    public array $pendingRevisions = [];

    abstract public function getRevisionModelClass(): string;

    public static function bootHasRevisions()
    {
        self::saving(static function(self $model): void {
            $dirty = $model->attributesToArray();
            if (isset($dirty['id'])) unset($dirty['id']);
            if ($model->getKey() && !empty($dirty)) {
                /** @var Model $revision */
                $revision = app()->make($model->getRevisionModelClass());
                $revision->fill([
                    'item_id' => $model->getKey(),
                    'user_id' => Auth::user()?->getKey(),
                    'data' => $dirty,
                ]);
                $model->pendingRevisions[] = $revision;
            }
        });
        self::saved(static function(self $model): void {
            if (!empty($model->pendingRevisions)) {
                foreach ($model->pendingRevisions as $revision) {
                    $revision->saveOrFail();
                }
                $model->pendingRevisions = [];
            }
        });
    }

    public function revisions(): HasMany
    {
        return $this->hasMany($this->getRevisionModelClass(), 'item_id')->orderBy('created_at','desc');
    }
}
