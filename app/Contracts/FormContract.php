<?php

declare(strict_types=1);

namespace App\Contracts;

use Illuminate\View\View;

interface FormContract
{
    public function render(): View|string;
    public function getID(): string;
}
