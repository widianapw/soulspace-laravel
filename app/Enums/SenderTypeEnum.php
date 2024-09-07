<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum SenderTypeEnum: string implements HasLabel
{
    case USER = "user";
    case BOT = "bot";


    public function getLabel(): ?string
    {
        return match ($this) {
            SenderTypeEnum::USER => "User",
            SenderTypeEnum::BOT => "Bot",
        };
    }
}
