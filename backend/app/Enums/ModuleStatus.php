<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum ModuleStatus: string implements HasColor, HasIcon, HasLabel
{
    case Connected = 'connected';
    case Disconnected = 'disconnected';

    case Error = 'error';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::Connected => 'Connected',
            self::Disconnected => 'Disconnected',
            self::Error => 'Error',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::Connected => 'success',
            self::Disconnected => 'warning',
            self::Error => 'danger',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::Connected => 'heroicon-m-check',
            self::Disconnected => 'heroicon-m-x-mark',
            self::Error => 'heroicon-o-exclamation-triangle',
        };
    }
}
