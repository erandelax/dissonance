<?php

declare(strict_types=1);

namespace App\Http\Actions\Web\Admins\Platform;

use App\Forms\ModelField;
use App\Forms\ModelForm;
use App\Models\Config;
use Illuminate\Http\Request;

final class AdminSettings
{
    public function __construct (
    ) {}

    public function read(Config $config)
    {
        return view('web.admins.read', ['form' => $this->form($config), 'title' => 'Settings']);
    }

    public function edit(Config $config, Request $request)
    {
        return view('web.admins.read', ['form' => $this->form($config)->submitRequest($request), 'title' => 'Settings']);
    }

    public function form(Config $config): ModelForm
    {
        $form = new ModelForm(
            id: 'settings',
            model: $config,
            fields: [
                new ModelField (
                    attribute: 'data.app.name',
                    title: 'Site name',
                    rules: [],
                ),
                new ModelField (
                    attribute: 'data.app.logo',
                    title: 'Site logo',
                    rules: [],
                ),
                new ModelField (
                    attribute: 'data.app.icon',
                    title: 'Site icon',
                    rules: [],
                ),
            ],
        );
        return $form;
    }
}
