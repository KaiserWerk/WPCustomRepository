<?php

class AuthHelper
{
    /**
     * Starts the session, independent from any authentication process
     *
     * @return null
     */
    public static function __init()
    {
        if (!self::isSessionStarted()) {
            session_start();
        }
        
        // set the csrf token once
        if (!isset($_SESSION['_csrf_token'])) {
            $_SESSION['_csrf_token'] = str_replace(".", "", uniqid('', true));
        }
        
        // Make sure we have a canary set
        if (!isset($_SESSION['canary'])) {
            if (self::isSessionStarted()) {
                session_regenerate_id();
                $_SESSION['sid'] = session_id();
                $_COOKIE[getenv('SESSNAME')] = session_id();
                $_SESSION['canary'] = time();
            }
        }
        // Regenerate session ID every five minutes:
        if ($_SESSION['canary'] < time() - 300) {
            if (self::isSessionStarted()) {
                session_regenerate_id();
                $_SESSION['sid'] = session_id();
                $_COOKIE[getenv('SESSNAME')] = session_id();
                $_SESSION['canary'] = time();
            }
        }
        return null;
    }
    
    /**
     * Checks whether the session is already started
     * session_status() is buggy, don't use it
     *
     * @return bool
     */
    private static function isSessionStarted()
    {
        #if (version_compare(phpversion(), '5.4.0', '>=')) {
        #    return (session_status() === PHP_SESSION_ACTIVE) ? true : false;
        #} else {
        return session_id() === '' ? false : true;
        #}
    }
    
    /**
     * Checks whether the CSRF token from a form is valid or not
     *
     * @param string $_csrf_token
     * @return bool
     */
    public static function checkCSRFToken($_csrf_token)
    {
        if ($_csrf_token !== null) {
            return $_csrf_token === $_SESSION['_csrf_token'];
        }
        return false;
    }
    
    public static function checkHoneypotInput($field)
    {
        if (empty($field)) {
            return true; // true = ok
        }
        return false;
    }
    
    /**
     * Returns true if the current user is logged in
     *
     * @return bool
     */
    public static function isLoggedIn()
    {
        if (isset($_SESSION['user']) && isset($_SESSION['sid'])) {
            return isset( $_COOKIE[getenv('SESSNAME')] ) && $_COOKIE[getenv('SESSNAME')] == $_SESSION['sid'];
        }
        return false;
    }
    
    /**
     * Returns the currently logged in user's username
     *
     * @return string
     */
    public static function getUsername()
    {
        $db = new DBHelper();
        if (self::isLoggedIn()) {
            $row = $db->get('user', [
                'username'
            ], [
                'id' => $_SESSION['user'],
            ]);
            return $row['username'];
        }
        return '-';
    }
    
    /**
     * Returns the currently logged in user's locale, e.g. 'de'
     *
     * @return string
     */
    public static function getUserLocale()
    {
        if (self::isLoggedIn()) {
            $db = new DBHelper();
            $row = $db->get('user', [
                'locale'
            ], [
                'id' => $_SESSION['user'],
            ]);
            return $row['locale'];
        } else {
            if (isset($_COOKIE[getenv('COOKIE_LANG')])) {
                return $_COOKIE[getenv('COOKIE_LANG')];
            }
        }
        return getenv('DEFAULT_LOCALE');
    }
    
    /**
     * Generates a random alphanumeric token, usually a confirmation token.
     * The foreign check for existing tokens can be ignored if you want to
     * generate a token for a different purpose.
     *
     * @param int $length
     * @param bool $ignore_foreign_check
     * @return string
     * @throws Exception
     */
    public static function generateToken($length = 25, $ignore_foreign_check = false)
    {
        $chars = '01234567890123456789abcdefghijklmnopqrstuvwxyz';
        if ($length > 20) {
            $chars.= 'ABCDEFGHIJKLMNPQRSTUVWXYZ0123456789';
        }
        $res = '';
        for ($i = 0; $i < $length; ++$i) {
            $res .= $chars[random_int(0, strlen($chars) - 1)];
        }
        
        //If confirmation token exists, generate another one
        if ($ignore_foreign_check !== true) {
            $db = new DBHelper();
            $bool = $db->has('user', [
                'OR' => [
                    'confirmation_token' => $res,
                    'apikey' => $res,
                ]
            ]);
            if ($bool) {
                return self::generateToken($length);
            }
        }
        return $res;
    }
    
    /**
     * Logs out the currently loggd in user
     *
     * @return bool
     */
    public static function logout()
    {
        @session_unset();
        @session_destroy();
        return true;
    }
    
    /**
     * Compares two strings (hashed) in a way timed attacks don't work
     *
     * @param $string1
     * @param $string2
     * @param int $compare_length
     * @return bool
     */
    public static function str_cmp_sec($string1, $string2, $compare_length = 75)
    {
        $str1_parts = str_split(str_pad((string)$string1, $compare_length, '0', STR_PAD_RIGHT));
        $str2_parts = str_split(str_pad((string)$string2, $compare_length, '0', STR_PAD_RIGHT));
        $result_array = array();
        $i = 0;
        foreach ($str1_parts as $part) {
            if ($part === $str2_parts[$i]) { // also see str_cmp() and similar_text()
                $result_array[$i] = true;
            } else {
                $result_array[$i] = false;
            }
            ++$i;
        }
        return (bool)array_product($result_array);
    }
    
    /**
     * Is the user with the supplied user ID an admin?
     *
     * @param $user_id
     * @return mixed
     */
    public static function isAdmin($user_id)
    {
        $db = new DBHelper();
        $bool = $db->has('user', 'admin', [
            'id' => (int)$user_id,
        ]);
        return $bool;
    }
    
    /**
     * Generates a hidden input field with the session's CSRF token.
     * Should be used in every form.
     *
     * @param bool $return
     * @return string
     */
    public static function generateCSRFInput($return = false)
    {
        $str = '<input type="hidden" name="_csrf_token" value="'.$_SESSION['_csrf_token'].'">'.PHP_EOL;
        if (!$return) {
            echo $str;
            return '';
        }
        return $str;
    }
    
    /**
     * Generates a hidden input field which should not be filled in.
     * Should be used in every form.
     *
     * @param bool $return
     * @return string
     */
    public static function generateHoneypotInput($return = false)
    {
        $str = '<input type="text" name="hp_name" class="form-control" style="visibility: hidden;">'.PHP_EOL;
        if (!$return) {
            echo $str;
            return '';
        }
        return $str;
    }
}