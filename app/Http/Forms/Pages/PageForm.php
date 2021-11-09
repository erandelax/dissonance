<?php

declare(strict_types=1);

namespace App\Http\Forms\Pages;

use App\Components\Forms\FormField;
use App\Components\Forms\FormFieldMarkdownPreview;
use App\Components\Forms\ModelForm;
use App\Helpers\LocaleHelper;
use App\Models\Page;
use App\Components\Forms\Fields;

final class PageForm extends ModelForm
{
    public function __construct(Page $page)
    {
        parent::__construct(id: 'pages',
            model: $page,
            fields: [
                [
                    new Fields\TextField(
                        attribute: 'id',
                        title: 'ID',
                        rules: ['required'],
                        readOnly: true,
                    ),
                    new Fields\TextField(
                        attribute: 'slug',
                        title: 'Slug',
                    ),
                    new Fields\SelectField(
                        options: LocaleHelper::getOptions(),
                        attribute: 'locale',
                        title: 'Locale',
                        rules: ['required'],
                    ),
                    new Fields\TextField(
                        attribute: 'title',
                        title: 'Title',
                        rules: ['required'],
                    ),
                    $source = new Fields\EditorField(
                        attribute: 'content',
                        title: 'Content',
                    ),
                    new Fields\TextField(
                        attribute: 'created_at',
                        title: 'Created at',
                        readOnly: true,
                    ),
                    new Fields\TextField(
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
