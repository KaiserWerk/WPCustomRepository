<?php

$klein->respond(array('GET', 'POST'), '/login', function () {
    AuthHelper::__init();
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        require_once getenv('TEMPLATE_PATH').'/auth/login.tpl.php';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $pdo = Config::getConnection();
       
        
        
    
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
    
        if (!isset($_POST['_csrf_token']) || !Auth::checkCSRFToken($_POST['_csrf_token'])) {
            /** write log */
            LoggerHelper::loginAttempt(null, 'Missing or invalid CSRF token.');
            LoggerHelper::debug( 'Login: Invalid CSRF Token from username ' . $cred['username']);
            /** send notification */
            $message = 'Login attempt with invalid CSRF token from IP '.Helper::getIP().'.';
            Communication::sendNotification($message);
            Helper::redirect('/login?e=unknown_error');
        }
    
        $stm = $pdo->prepare("SELECT id, username, password, email, locked FROM `user` WHERE username = ?");
        $stm->execute(array($cred['username']));
        if ($stm->rowCount() !== 1) {
            LoggerHelper::loginAttempt(null, 'Username does not exist.');
            Helper::redirect('/login?e=incorrect_credentials');
        }
    
        $row = $stm->fetch(PDO::FETCH_ASSOC);
        if ($row['locked'] == 1) {
            LoggerHelper::loginAttempt($row['id'], 'Account is locked.');
            Helper::redirect('/login?e=account_locked');
        }
    
    
        if (password_verify($cred['password'], $row['password'])) {
            /** From here on, user is correctly authenticated */
        
            // null-ify confirmation token
            $stm = $pdo->prepare("UPDATE user SET confirmation_token = NULL, confirmation_token_validity = NULL, last_login = CURRENT_TIMESTAMP WHERE id = ?");
            $stm->execute(array($row['id']));
        
            // remove login counter cookie
            setcookie('va_la', '', time() - 10);
        
            $options = array('cost' => 12);
            if (password_needs_rehash($row['password'], PASSWORD_BCRYPT, $options)) {
                LoggerHelper::debug( 'Login: Password for user ' . $cred['username'] . ' rehashed');
                // If so, create a new hash, and replace the old one
                $newHash = password_hash($cred['password'], PASSWORD_BCRYPT, $options);
                // save new hash
                $stm = $pdo->prepare('UPDATE `user` SET password = ? WHERE id = ?');
                $stm->execute(array($newHash, $row['id']));
            }
            // continue with login
        
            $_SESSION['user'] = $row['id'];
            $_SESSION['sid'] = session_id();
        
            LoggerHelper::loginAttempt($row['id'], 'Successful login.');
            Helper::redirect('/verdicts/overview');
        } else {
        
            if (isset($_COOKIE['va_la']) && $_COOKIE['va_la'] >= 5) {
                // lock account
                $stm = $pdo->prepare('UPDATE user SET locked = 1 WHERE id = ?');
                $stm->execute(array($row['id']));
                // send HipChat message
                $message = 'The user account '.$row['username'].' ('.$row['id'].') was locked due to too many login attempts.';
                Communication::sendNotification($message);
                // reset cookie
                setcookie('va_la', '', time() - 10);
                Helper::redirect('/login?e=too_many_attempts');
            }
            $message = 'Failed login attempt from IP '.Helper::getIP().' with username '.$cred['username'];
            Communication::sendNotification($message);
            Helper::redirect('/login?e=incorrect_credentials');
        }
        
    }
});

$klein->respond('GET', '/logout', function ($request) {

});