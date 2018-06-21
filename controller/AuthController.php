<?php

$router->respond(['GET', 'POST'], '/login', function () {
    
    if (AuthHelper::isLoggedIn()) {
        Helper::setMessage('You are already logged in.');
        Helper::redirect('/');
    }
    
    if (isset($_POST['btn_login'])) {
        $db = new DBHelper();
        $cred = $_POST['_login'];
        if (isset($_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')])) {
            $va_la = $_COOKIE[getenv('COOKIE_LOGIN_ATTEMPT')];
        } else {
            $va_la = 0;
        }
        
        setcookie(getenv('COOKIE_LOGIN_ATTEMPT'), $va_la + 1, time() + 60 * 60 * 24 * 7);
    
        LoggerHelper::debug('Starting login process');
    
        if (empty($cred['username']) || empty($cred['password'])) {
            LoggerHelper::loginAttempt(null, 'Missing username or password.');
            Helper::setMessage('Please enter both your username/email and password.');
            Helper::redirect('/login');
        }
    
        if (!AuthHelper::checkCSRFToken()) {
            /** write log */
            LoggerHelper::loginAttempt(null, 'Missing or invalid CSRF token.');
            LoggerHelper::debug( 'Login: Invalid CSRF Token from username ' . $cred['username']);
            /** send notification */
            $message = 'Login attempt with invalid CSRF token from IP ' . Helper::getIP() . '.';
            CommunicationHelper::sendNotification($message);
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/login');
        }
        
        if (!AuthHelper::checkHoneypot()) {
            /** write log */
            LoggerHelper::loginAttempt(null, 'Honeypot error - Bot detected?');
            LoggerHelper::debug( 'Login: Honeypot error from username ' . $cred['username']);
            /** send notification */
            $message = 'Login attempt with Honeypot error from IP ' . Helper::getIP() . '.';
            CommunicationHelper::sendNotification($message);
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/login');
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
            LoggerHelper::debug('failed login', 'error');
            Helper::setMessage('You entered incorrect credentials!', 'danger');
            Helper::redirect('/login');
        }
    
        $row = $rows[0];
        if ($row['locked'] === 1) {
            LoggerHelper::loginAttempt($row['id'], 'Account is locked.');
            LoggerHelper::debug('account locked', 'warn');
            Helper::setMessage('Your account is locked!', 'warning');
            Helper::redirect('/login');
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
            
            Helper::setMessage('You are now logged in!', 'success');
            Helper::redirect('/');
        } else {
            LoggerHelper::loginAttempt(null, 'Unknown credentials.');
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
                setcookie(getenv('COOKIE_LOGIN_ATTEMPT'), '', time() - 10);#
                Helper::setMessage('Your account was locked due to too many failed login attempts!', 'warning');
                Helper::redirect('/login?e=too_many_attempts');
            }
            $message = 'Failed login attempt from IP '.Helper::getIP().' with username '.$cred['username'];
            CommunicationHelper::sendNotification($message);
            
            Helper::setMessage('You entered incorrect credentials!', 'danger');
            Helper::redirect('/login');
        }
    } else {
        Helper::renderPage('/auth/login.tpl.php');
    }
});

$router->respond('GET', '/logout', function ($request) {
    if (AuthHelper::isLoggedIn()) {
        AuthHelper::logout();
    }
    
    Helper::setMessage('You are now logged out.');
    Helper::redirect('/');
});

/**
 * Displays the reset request form
 */
$router->respond(['GET', 'POST'], '/resetting/request', function ($request) {
    $db = new DBHelper();
    if (isset($_POST['btn_reset_request'])) {
        $cred = $_POST['_reset_request'];
        if (empty($cred['username'])) {
            Helper::setMessage('Please enter your username!', 'warning');
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
            ], [
                'OR' => [
                    'username' => $cred['username'],
                    'email' => $cred['username'],
                ],
            ]);
            
            $token = AuthHelper::generateToken(20);
    
            $date = new \DateTime();
            $date->add(new DateInterval('PT6H'));
            
            $db->update('user', [
                'confirmation_token' => $token,
                'confirmation_token_validity' => $date->format('Y-m-d H:i:s'),
            ], [
                'id' => $row['id'],
            ]);
            
            /** e-mail eintragen */
            $emailValues = [
                'username' => $row['username'],
                'confirmation_token' => $token,
            ];
            if ((bool)getenv('EMAIL_TRACKING_ENABLED') === true) {
                $emailValues['tracking_token'] =
                    Helper::generateEmailTrackingToken($row['username'] . ' <' . $row['email'] . '>', $token);
            }
            $body = file_get_contents(viewsDir() . '/email/reset_request.tpl.html');
            $body = Helper::insertValues($body, $emailValues);
            
            // send mail
            CommunicationHelper::sendMail(
                $body,
                'WPCustomRepository - Reset password',
                $row['email'],
                $row['username'],
                getenv('MAILER_USER'),
                getenv('MAILER_USER_NAME'),
                getenv('MAILER_REPLYTO'),
                getenv('MAILER_REPLYTO_NAME')
            );

            Helper::setMessage('You requested a new password! Check your inbox.', 'success');
            Helper::redirect('/resetting/request');
        } else {
            // user not found. still send out success message to not
            // disclose that the user does not exists
            Helper::setMessage('You requested a new password! Check your inbox.', 'success');
            Helper::redirect('/resetting/request');
        }
    } else {
        Helper::renderPage('/auth/reset_request.tpl.php');
    }
});

/**
 * Resets a user's password
 */
$router->respond(['GET', 'POST'], '/resetting/reset', function ($request) {
    $db = new DBHelper();
    
    $token = isset($_REQUEST['confirmation_token']) ? trim($_REQUEST['confirmation_token']) : null;
    if ($token === null || !preg_match('/^[A-Za-z0-9-]+/', $token)) {
        $token = trim($_GET['_confirmation_token']);
    }
    
    // darf leer sein, also nicht weiterleiten!
    /*if (empty($token) || !preg_match('/^[A-Za-z0-9-]+/', $token)) {
        #Helper::setMessage('You supplied an invalid reset Token!', 'danger');
        #Helper::redirect('/resetting/reset');
    }*/
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
        
        AuthHelper::requireValidCSRFToken();
        
        if (!$db->has('user', [
            'confirmation_token' => $token,
        ])) {
            Helper::setMessage('Unknown error 1!', 'danger');
            Helper::redirect('/resetting/reset');
        }
        $row = $db->get('user', [
            'id',
            'password',
        ], [
            'confirmation_token' => $token,
        ]);
        
        $cred = $_POST['_reset_reset'];
        if (empty($cred['password1']) || empty($cred['password2'])) {
            Helper::setMessage('Please fill in all fields!', 'warning');
            Helper::redirect('/resetting/reset?confirmation_token=' . $token);
        }
        if (!AuthHelper::str_cmp_sec($cred['password1'], $cred['password2'])) {
            Helper::setMessage('The passwords you entered do not match!', 'danger');
            Helper::redirect('/resetting/reset?confirmation_token=' . $token);
        }
        
        if (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.[\W]).{12,}$/', $cred['password1'])) {
            Helper::setMessage('A new password must be at least 12 characters long and contain lowercase, uppercase and numbers!', 'warning');
            Helper::redirect('/resetting/reset?confirmation_token=' . $token);
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
        
        Helper::setMessage('You successfully reset your password!', 'success');
        Helper::redirect('/resetting/reset');
    } else {
        $token = isset($_GET['confirmation_token']) ? trim($_GET['confirmation_token']) : '';
        if (!empty($token)) {
            if (!preg_match('/^[A-Za-z0-9-]+/', $token)) {
                Helper::setMessage('You entered an invalid reset token!', 'danger');
                Helper::redirect('/resetting/reset');
            }
            if (!$db->has('user', [
                'confirmation_token' => $token,
            ])) {
                Helper::setMessage('You entered an invalid reset token!', 'danger');
                Helper::redirect('/resetting/reset');
            }
        }
        
        Helper::renderPage('/auth/reset_reset.tpl.php', [
            'token' => $token,
        ]);
    }
});