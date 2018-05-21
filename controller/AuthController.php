<?php

$klein->respond(['GET', 'POST'], '/login', function () {
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (AuthHelper::isLoggedIn()) {
            die("already logged in");
        }
        require_once viewsDir().'/header.tpl.php';
        require_once viewsDir().'/auth/login.tpl.php';
        require_once viewsDir().'/footer.tpl.php';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $db = new DBHelper();
        $cred = $_POST['_login'];
        if (isset($_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')])) {
            $va_la = $_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')];
        } else {
            $va_la = 0;
        }
        setcookie(getenv('COOKIE_LOGIN_ATTEMPT'), $va_la + 1, time() + 60*60*24*7);
    
        LoggerHelper::debug('Starting login process');
    
    
        if (empty($cred['username']) || empty($cred['password'])) {
            LoggerHelper::loginAttempt(null, 'Missing username or password.');
            Helper::redirect('/login?e=missing_input');
        }
    
        if (!isset($_POST['_csrf_token']) || !AuthHelper::checkCSRFToken($_POST['_csrf_token'])) {
            /** write log */
            LoggerHelper::loginAttempt(null, 'Missing or invalid CSRF token.');
            LoggerHelper::debug( 'Login: Invalid CSRF Token from username ' . $cred['username']);
            /** send notification */
            $message = 'Login attempt with invalid CSRF token from IP '.Helper::getIP().'.';
            CommunicationHelper::sendNotification($message);
            Helper::redirect('/login?e=unknown_error');
        }
        $rows = $db->select('user', [
            'id',
            'username',
            'password',
            'email',
            'locked',
        ], [
            'username' => $cred['username'],
        ]);
        
        if (count($rows) !== 1) {
            LoggerHelper::loginAttempt(null, 'Username does not exist.');
            Helper::redirect('/login?e=incorrect_credentials');
        }
    
        $row = $rows[0];
        if ($row['locked'] == 1) {
            LoggerHelper::loginAttempt($row['id'], 'Account is locked.');
            Helper::redirect('/login?e=account_locked');
        }
    
        if (password_verify($cred['password'], $row['password'])) {
            /** From here on, user is correctly authenticated */
        
            // null-ify confirmation token
            $db->update('user', [
                'confirmation_token' => null,
                'confirmation_token_validity' => null,
                'last_login' => date('Y-m-d H:i:s'),
            ], [
                'id' => $row['id'],
            ]);
        
            // remove login counter cookie
            setcookie(getenv('COOKIE_LOGIN_ATTEMPT'), '',time() - 10);
        
            $options = ['cost' => 12];
            if (password_needs_rehash($row['password'], PASSWORD_BCRYPT, $options)) {
                LoggerHelper::debug( 'Login: Password for user ' . $cred['username'] . ' rehashed');
                // If so, create a new hash, and replace the old one
                $newHash = password_hash($cred['password'], PASSWORD_BCRYPT, $options);
                // save new hash
                $db->update('user', [
                    'password' => $newHash,
                ], [
                    'id' => $row['id'],
                ]);
            }
            // continue with login
            $_SESSION['user'] = $row['id'];
            $_SESSION['sid'] = session_id();
        
            LoggerHelper::loginAttempt($row['id'], 'Successful login.');
            Helper::redirect('/');
        } else {
            if (isset($_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')]) && $_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')] >= 5) {
                // lock account
                $db->update('user', [
                    'locked' => 1,
                ], [
                    'id' => $row['id'],
                ]);
            
                $message = 'The user account '.$row['username'].' ('.$row['id'].') was locked due to too many login attempts.';
                CommunicationHelper::sendNotification($message);
                // reset cookie
                setcookie(getenv('COOKIE_LOGIN_ATTEMPT'), '', time() - 10);
                Helper::redirect('/login?e=too_many_attempts');
            }
            $message = 'Failed login attempt from IP '.Helper::getIP().' with username '.$cred['username'];
            CommunicationHelper::sendNotification($message);
            Helper::redirect('/login?e=incorrect_credentials');
        }
    }
});

$klein->respond('GET', '/logout', function ($request) {
    if (AuthHelper::isLoggedIn()) {
        AuthHelper::logout();
    }
    Helper::redirect('/');
});