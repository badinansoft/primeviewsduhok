<?php

namespace App\Enums;

enum UserRoles : string
{
    case ADMIN = 'admin';
    case ADMIN_VIEWER = 'viewer';
    case NORMAL_USER = 'user';
}
