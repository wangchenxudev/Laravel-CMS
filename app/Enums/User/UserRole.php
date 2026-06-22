<?php

namespace App\Enums\User;

enum UserRole: string
{
    case User = 'user';
    case Admin = 'admin';
}
