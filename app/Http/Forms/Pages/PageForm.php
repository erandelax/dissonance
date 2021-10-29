<?php

declare(strict_types=1);

namespace App\Http\Forms\Pages;

use App\Components\Forms\FormField;
use App\Components\Forms\FormFieldMarkdownPreview;
use App\Components\Forms\ModelForm;
use App\Helpers\LocaleHelper;
use App\Models\Page;

final class PageForm extends ModelForm
{
    public function __construct(Page $page)
    {
        parent::__construct(id: 'pages',
            model: $page,
            fields: [
                [
                    new FormField(
                        attribute: 'id',
                        title: 'ID',
                        rules: ['required'],
                        readOnly: true,
                    ),
                    new FormField(
                        attribute: 'slug',
                        title: 'Slug',
                    ),
                    new FormField(
                        attribute: 'locale',
                        style: FormField::STYLE_SELECT,
                        title: 'Locale',
                        options: LocaleHelper::getOptions(),
                        rules: ['required'],
                    ),
                    new FormField(
                        attribute: 'title',
                        title: 'Title',
                        rules: ['required'],
                    ),
                    $source = new FormField(
                        attribute: 'content',
                        style: FormField::STYLE_MARKDOWN,
                        title: 'Content',
                    ),
                    new FormField(
                        attribute: 'created_at',
                        title: 'Created at',
                        readOnly: true,
                    ),
                    new FormField(
                        attribute: 'updated_at',
                        title: 'Updated at',
                        readOnly: true,
                    ),
                ],
                [
                    new FormFieldMarkdownPreview(
                        source: $source,
                        useLabelColumn: false,
                    ),
                ]
            ]);
    }
}
