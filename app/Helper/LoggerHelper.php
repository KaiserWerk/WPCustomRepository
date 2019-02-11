<?php

class LoggerHelper
{
    /**
     * Writes a debug log file
     *
     * External writing means sending data to a logging API
     *
     * @param string $content
     * @param string $log_level
     * @return bool
     */
    public static function debug($content, $log_level = 'debug')
    {
        $log_levels = array(
            'debug',
            'info',
            'warn',
            'error',
            'crit',
        );

        $dir = VARDIR . '/logs';
        if (!is_dir($dir)) {
            @mkdir($dir, 0775);
        }

        $file_single = $dir . '/debug.log';

        $h = @fopen($file_single, 'ab+');
        if ($h !== false) {
            if (!in_array($log_level, $log_levels, true)) {
                $log_level = 'debug';
            }
            $log_line_raw = str_pad(strtoupper($log_level), 5, ' ', STR_PAD_LEFT);
            $log_line = '['.date('Y-m-d H:i:s').'] [' . Helper::getIP() . '] [' . $log_line_raw . '] ' . $content . PHP_EOL;
            @fwrite($h, $log_line);
            @fclose($h);
        } else {
            return false;
        }
        
        return true;
    }
    
    /**
     * Logs a login attempt (be it successful or not)
     *
     * @param $user_entry_id
     * @param $login_status
     * @return bool
     */
    public static function loginAttempt($user_entry_id, $login_status)
    {
        try {
            $db = new DBHelper();
            $db->insert('login', [
                'user_entry_id' => $user_entry_id,
                'login_status' => $login_status,
                'useragent' => $_SERVER['HTTP_USER_AGENT'],
                'lang' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                'ip' => Helper::getIP(),
            ]);
        } catch (Exception $e) {
            return false;
        }
        return true;
    }
    
    /**
     * @param string $target
     * @param string $method
     * @param array $request_headers
     * 
     * @return bool
     */
    public static function logAPIRequest($target, $method, $request_headers)
    {
        $db = new DBHelper();
        $db->insert('api_request', [
            'target' => $target,
            'method' => $method,
            'request_headers' => print_r($request_headers, true),
        ]);
        return true;
    }
}