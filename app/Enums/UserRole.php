<?php

namespace App\Enums;

enum UserRole: string
{
    case Owner     = 'owner';
    case Admin     = 'admin';

    public function label(): string
    {
        return match($this) {
            self::Owner     => 'Owner',
            self::Admin     => 'Administrator',
        };
    }

    public function isProtected(): bool
    {
        return in_array($this, [self::Owner]);
    }

    public function isImmutable(): bool
    {
        return $this === self::Owner;
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn($c) => [$c->value => $c->label()])
            ->toArray();
    }
}
