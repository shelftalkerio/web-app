<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum UserRole: string implements HasColor, HasIcon, HasLabel
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';

    case Manager = 'manager';
    case Store = 'store';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Admin => 'Admin',
            self::Manager => 'Manager',
            self::Store => 'Store User',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::SuperAdmin => 'success',
            self::Admin => 'warning',
            self::Manager => 'danger',
            self::Store => 'info',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::SuperAdmin => 'heroicon-m-user-circle',
            self::Admin => 'user-plus',
            self::Manager => 'heroicon-m-users',
            self::Store => 'heroicon-m-user',
        };
    }

    public static function panelAccessRoles(): array
    {
        return [
            self::SuperAdmin,
            self::Admin,
            self::Manager,
            self::Store,
        ];
    }
}
