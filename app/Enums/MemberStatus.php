<?php

namespace App\Enums;

enum MemberStatus: string
{
    case Active = 'active';
    case Invited = 'invited';
    case Suspended = 'suspended';
    case Removed = 'removed';

    public function label(): string
    {
        return match($this) {
            self::Active => 'Active',
            self::Invited => 'Invited',
            self::Suspended => 'Suspended',
            self::Removed => 'Removed',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Active => 'green',
            self::Invited => 'yellow',
            self::Suspended => 'red',
            self::Removed => 'red',
        };
    }
}
