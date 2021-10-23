<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\PageRevision;

final class PageRevisionRepository
{
    /**
     * @param string $id
     * @return PageRevision|null
     */
    public function findByID(string $id): ?PageRevision
    {
        return PageRevision::find($id);
    }
}
