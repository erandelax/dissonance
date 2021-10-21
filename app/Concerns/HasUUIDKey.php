<?php

declare(strict_types=1);

namespace App\Concerns;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

/**
 * Trait HasUUIDKey
 * @package App\Concerns
 * @mixin Model
 */
trait HasUUIDKey
{
    public static function bootHasUUIDKey()
    {
        self::saving(static function(Model $model) {
            $model->getKey() !== null ?: $model->{$model->getKeyName()} = Uuid::uuid4()->toString();
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }
}
