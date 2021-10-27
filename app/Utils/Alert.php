<?php

declare(strict_types=1);

namespace App\Utils;

use Illuminate\Support\Facades\Session;

final class Alert implements \JsonSerializable
{
    public const TYPE_INFO = 'alert-primary';
    public const TYPE_SUCCESS = 'alert-success';
    public const TYPE_WARNING = 'alert-secondary';
    public const TYPE_DANGER = 'alert-danger';

    public function __construct(
        private string      $content,
        private string|null $title = null,
        private string|null $alertType = self::TYPE_INFO,
        private string|null $fillType = null,
        private bool        $hasDismissButton = true,
        private int         $timeShown = 3000,
    )
    {
    }

    public function dispatch(): void
    {
        $alerts = Session::get('alerts', []);
        $alerts[] = $this;
        Session::put('alerts', $alerts);
    }

    public static function serialize(): string
    {
        $result = json_encode(Session::get('alerts', []));
        Session::forget('alerts');
        return $result;
    }

    public function jsonSerialize(): array
    {
        return array_filter([
            'content' => $this->content,
            'title' => $this->title,
            'alertType' => $this->alertType,
            'fillType' => $this->fillType,
            'hasDismissButton' => $this->hasDismissButton,
            'timeShown' => $this->timeShown,
        ]);
    }
}
