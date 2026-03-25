<?php

if (!function_exists('canUser')) {
    function canUser($permission)
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasPermission($permission);
    }
}

if (!function_exists('isSuperAdmin')) {
    function isSuperAdmin()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->hasRole('super-admin');
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (!auth()->check()) {
            return false;
        }
        
        return auth()->user()->isAdmin();
    }
}