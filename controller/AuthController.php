<?php

$klein->respond(['GET', 'POST'], '/login', function () {
    
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        if (AuthHelper::isLoggedIn()) {
            die();
        }
        require_once viewsDir().'/header.tpl.php';
        require_once viewsDir().'/auth/login.tpl.php';
        require_once viewsDir().'/footer.tpl.php';
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // @TODO use the submit button name instead
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
    
        if (!AuthHelper::checkCSRFToken()) {
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

/**
 * Displays the reset request form
 */
$klein->respond(['GET', 'POST'], '/resetting/request', function ($request) {
    $db = new DBHelper();
    if (isset($_POST['btn_reset_request'])) {
        $cred = $_POST['_reset_request'];
        if (empty($cred['username'])) {
            Helper::redirect('/resetting/request?e=missing_input');
        }
        if ($db->has('user', [
            'OR' => [
                'username' => $cred['username'],
                'email' => $cred['username'],
            ],
        ])) {
            
            $row = $db->get('user', [
                'id',
                'username',
                'email'
            ]);
            
            $token = AuthHelper::generateToken(20);
    
            $date = new \DateTime();
            $date->add(new DateInterval('PT6H'));
            
            $db->update('user',[
                'confirmation_token' => $token,
                'confirmation_token_validity' => $date->format('Y-m-d H:i:s'),
            ], [
                'id' => $row['id'],
            ]);
            
            /** e-mail eintragen */
            if (getenv('EMAIL_TRACKING_ENABLED') === 'true') {
                $trackingToken = Helper::generateEmailTrackingToken($row['username'] . " <" . $row['email'] . ">",
                    $token);
                $body = Helper::insertValues(viewsDir() . '/email/reset_request.tpl.html', [
                    'username' => $row['username'],
                    'confirmation_token' => $token,
                    'tracking_token' => $trackingToken,
                ]);
            } else {
                $body = file_get_contents(viewsDir() . '/email/reset_request.tpl.html');
                $body = Helper::insertValues($body, [
                    'username' => $row['username'],
                    'confirmation_token' => $token,
                ]);
            }
            
            // send mail
            CommunicationHelper::sendMail(
                $body,
                TranslationHelper::_t('email.reset_request.subject', true),
                $row['email'],
                $row['username'],
                getenv('MAILER_USER'),
                getenv('MAILER_USER_NAME'),
                getenv('MAILER_REPLYTO'),
                getenv('MAILER_REPLYTO_NAME')
            );
            
            Helper::redirect('/resetting/request?e=success');
        } else {
            // user not found. still send out success message to not
            // disclose that the user does not exists
            Helper::redirect('/resetting/request?e=success');
        }
    } else {
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/auth/reset_request.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});

/**
 * Resets a user's password
 */
$klein->respond(['GET', 'POST'], '/resetting/reset', function ($request) {
    $db = new DBHelper();
    
    $token = isset($_GET['confirmation_token']) ? trim($_GET['confirmation_token']) : null;
    if ($token === null || !preg_match('/^[A-Za-z0-9-]+/', $token)) {
        $token = trim($_GET['_confirmation_token']);
        if (empty($token) || !preg_match('/^[A-Za-z0-9-]+/', $token)) {
            Helper::redirect('/resetting/reset?e=invalid_token');
        }
    }
    // mail tracking
    if (getenv('EMAIL_TRACKING_ENABLED') === 'true') {
        $db->update('mail_sent', [
            'token_used_at' => date('Y-m-d H:i:s'),
        ], [
            'confirmation_token' => $token,
        ]);
    }
    
    // Formular wurde abgeschickt
    if (isset($_POST['btn_reset_reset'])) {
        // token vorbereiten
        if ($_POST['_csrf_token'] !== $_SESSION['_csrf_token']) {
            Helper::redirect('/resetting/reset?e=unknown_error');
        }
        if (!$db->has('user', [
            'confirmation_token' => $token,
        ])) {
            Helper::redirect('/resetting/reset?e=invalid_token');
        }
        $row = $db->get('user', [
            'id',
            'password',
        ], [
            'confirmation_token' => $token,
        ]);
        
        $cred = $_POST['_reset_reset'];
        if (empty($cred['password1']) || empty($cred['password2'])) {
            Helper::redirect('/resetting/reset?confirmation_token='.$token.'&e=missing_input');
        }
        if (!AuthHelper::str_cmp_sec($cred['password1'], $cred['password2'])) {
            Helper::redirect('/resetting/reset?confirmation_token='.$token.'&e=passwords_not_equal');
        }
        
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.[\W]).{12,}$/', $cred['password1'])) {
            Helper::redirect('/resetting/reset?confirmation_token='.$token.'&e=password_complexity');
        }
        
        $hash = password_hash($cred['password1'], PASSWORD_BCRYPT, ['cost' => 12]);
        
        $db->update('user', [
            'confirmation_token' => null,
            'password' => $hash,
        ], [
            'id' => $row['id'],
        ]);
        
        LoggerHelper::debug($hash);
        LoggerHelper::debug($row['id']);
        
        Helper::redirect('/resetting/reset?e=success');
    } else {
        $token = isset($_GET['confirmation_token']) ? trim($_GET['confirmation_token']) : '';
        if (!empty($token)) {
            if (!preg_match('/^[A-Za-z0-9-]+/', $token)) {
                Helper::redirect('/resetting/reset?e=invalid_token');
            }
            if (!$db->has('user', [
                'confirmation_token' => $token,
            ])) {
                Helper::redirect('/resetting/reset?e=invalid_token');
            }
        }
        
        require_once viewsDir() . '/header.tpl.php';
        require_once viewsDir() . '/auth/reset_reset.tpl.php';
        require_once viewsDir() . '/footer.tpl.php';
    }
});