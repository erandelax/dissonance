<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Contracts\FormContract;
use App\Forms\ModelColumn;
use App\Forms\ModelUrlAction;
use App\Forms\QueryFilter;
use App\Forms\QueryTable;
use App\Models\Upload;
use App\Models\User;

final class BrowseAndReadUploads
{

    public function browse()
    {
        return view('web.admins.read', [
            'title' => 'Uploads',
            'form' => $this->table(),
        ]);
    }

    private function table(): FormContract
    {
        $table = new QueryTable(
            query: Upload::query(),
            id: 'browse-uploads',
            pageSize: 5,
            columns: [
                new ModelColumn(
                    title: 'Actions',
                    actions: [
                        new ModelUrlAction(
                            title: '<i class="fas fa-download"></i>',
                            urlMaker: fn(Upload $model) => $model->url,
                        ),
                    ],
                ),
                new ModelColumn(
                    attribute: 'id',
                    title: 'ID',
                    filter: new QueryFilter(id: 'id', rules: ['uuid'])
                ),
                new ModelColumn(
                    attribute: 'mime',
                    title: 'Mime type',
                    filter: new QueryFilter(id: 'id', rules: [])
                ),
                new ModelColumn(
                    attribute: 'size',
                    title: 'Size',
                    filter: new QueryFilter(id: 'id', rules: ['numeric'])
                ),
                new ModelColumn(
                    attribute: 'preview_url',
                    title: 'Preview',
                    style: ModelColumn::STYLE_IMAGE,
                ),
                new ModelColumn(
                    attribute: 'created_at',
                    title: 'Created At',
                    filter: new QueryFilter(style: QueryFilter::STYLE_DATE, id: 'created_at'),
                ),
                new ModelColumn(
                    attribute: 'updated_at',
                    title: 'Updated At',
                    filter: new QueryFilter(style: QueryFilter::STYLE_DATE, id: 'updated_at'),
                ),
            ],
        );
        return $table;
    }
}
