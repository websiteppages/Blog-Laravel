<?php

namespace App\Enums;

enum ActorType: string
{
    case User = 'user';
    case System = 'system';
    case Api = 'api';
}
