<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface FormFilterContract extends FormContract
{
    public function apply(mixed $input, Builder $query): void;
}
