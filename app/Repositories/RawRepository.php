<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Enums\RawTypeEnum;
use App\Models\Raw;

final class RawRepository
{
    public function make()
    {
        return Raw::make([
            'type' => RawTypeEnum::TEXT(),
        ]);
    }

    public function findMakeOrFail(string $id)
    {
        if ($id === 'new') {
            return $this->make();
        }
        return Raw::findOrFail($id);
    }
}
