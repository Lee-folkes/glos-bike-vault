<?php

// This Enum defines the different user roles in the application.

namespace App\Enums;

enum UserRole: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case EDITOR = 'editor'; // Add more as needed
}

