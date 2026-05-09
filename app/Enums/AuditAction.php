<?php

namespace App\Enums;

/**
 * All audit action verbs.
 *
 * Design: a small controlled vocabulary of verbs keeps queries and filters
 * consistent. Event strings (post.viewed, post.created) carry the noun;
 * action strings carry the verb. This separation lets you query
 * "all view events" with a single ->where('action', AuditAction::View->value)
 * without pattern-matching event strings.
 */
enum AuditAction: string
{
    case Create  = 'create';
    case Update  = 'update';
    case Delete  = 'delete';
    case View    = 'view';
    case Login   = 'login';
    case Logout  = 'logout';
    case Publish = 'publish';
    case Export  = 'export';

    public function label(): string
    {
        return match($this) {
            self::Create  => 'Created',
            self::Update  => 'Updated',
            self::Delete  => 'Deleted',
            self::View    => 'Viewed',
            self::Login   => 'Logged in',
            self::Logout  => 'Logged out',
            self::Publish => 'Published',
            self::Export  => 'Exported',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Create  => 'green',
            self::Update  => 'blue',
            self::Delete  => 'red',
            self::View    => 'gray',
            self::Login   => 'indigo',
            self::Logout  => 'indigo',
            self::Publish => 'emerald',
            self::Export  => 'yellow',
        };
    }
}
