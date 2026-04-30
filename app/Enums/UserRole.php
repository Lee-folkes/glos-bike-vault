<?php

/**
 * This Enum defines the different user roles in the application.
 * The purpose of this Enum is to provide a clear and consistent way to manage user roles throughout the application 
 * and avoid ambiguity in role definitions. Each role can have specific permissions and access levels, 
 * which can be easily referenced using this Enum.
 * Additional roles can be added as needed.
 */

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case EDITOR = 'editor';
}

