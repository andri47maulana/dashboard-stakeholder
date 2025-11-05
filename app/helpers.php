<?php

if (!function_exists('isAdmin')) {
    /**
     * Check if the current authenticated user is an Admin
     *
     * @return bool
     */
    function isAdmin()
    {
        return auth()->check() && auth()->user()->hakakses === 'Admin';
    }
}

if (!function_exists('getUserRegion')) {
    /**
     * Get the current authenticated user's region
     *
     * @return string|null
     */
    function getUserRegion()
    {
        return auth()->check() ? auth()->user()->region : null;
    }
}

if (!function_exists('getUserHakAkses')) {
    /**
     * Get the current authenticated user's hakakses level
     *
     * @return string|null
     */
    function getUserHakAkses()
    {
        return auth()->check() ? auth()->user()->hakakses : null;
    }
}
