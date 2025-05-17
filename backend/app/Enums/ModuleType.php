<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ModuleType: string implements HasColor, HasIcon, HasLabel
{
    case Epos = 'epos';
    case Esl = 'esl';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Epos => 'EPOS',
            self::Esl => 'ESL',
        };
    }

    public function getImage(): string
    {
        return match ($this) {
            self::Epos => asset('images/modules/epos.png'),
            self::Esl => asset('images/modules/esl.png'),
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Epos => 'info',
            self::Esl => 'success',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Epos => 'heroicon-m-check',
            self::Esl => 'heroicon-m-x-mark',
        };
    }
}
