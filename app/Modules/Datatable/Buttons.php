<?php

declare(strict_types=1);

namespace App\Modules\Datatable;

abstract class Buttons
{
    public static function actionButton(
        string $eventName,
        string $text,
        string $color
    ) {
        return "<a class='btn $color btn-sm ms-1 me-1' href='javascript:void(0)' onclick='$eventName'>$text</a>";
    }
}
