<?php
if(!defined('APP_ROOT')) {
    exit('No direct script access allowed');
}

class Validation
{
    /******************
     * Text Functions *
     ******************/
    /**
     * Check if first name is valid
     * @return boolean
     */
    public static function notBlank($string) {
        return $string != '';
    }

    /**
     * Check if first name is valid
     * @return boolean
     */
    public static function first_name($first_name) {
        return $first_name != '';
    }

    /**
     * Check if last name is valid
     * @return boolean
     */
    public static function last_name($last_name) {
        return $last_name != '';
    }

    /**
     * Check if email address is valid
     * @return boolean
     */
    public static function email($email) {
        return strpos($email, '@');
    }

    /**
     * Check if password is valid
     * @return boolean
     */
    public static function password($password) {
        // Assume valid
        $pw_valid = TRUE;

        // Check length
        $pw_valid = strlen($password) > 6 && strlen($password) < 30;

        // Add other checks as ness

        return $pw_valid;
    }

    /**
     * Check if two strings match
     * @return boolean
     */
    public static function match($string1, $string2) {
        return is_string($string1) && is_string($string2) && $string1 == $string2;
    }
}