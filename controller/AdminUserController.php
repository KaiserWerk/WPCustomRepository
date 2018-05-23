<?php

/**
 * List all users
 */
$klein->respond('GET', '/admin/user/list', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    
    $users = $db->select('user', [
        'id',
        'username',
        'email',
        'locked',
        'admin',
    ], [
        'ORDER' => [
            'username' => 'ASC',
        ],
    ]);

    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/user/list.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

/**
 * Change the 'locked' status of a user
 */
$klein->respond('GET', '/admin/user/status/locked', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id !== null) {
        $row = $db->get('user', [
            'locked',
        ], [
            'id' => $id,
        ]);
        
        $db->update('user',[
            'locked' => abs($row['id'] - 1),
        ], [
            'id' => $id,
        ]);

        Helper::redirect('/admin/user/list');
    }
});

/**
 * Change the admin status of a user
 * (not of oneself)
 */
$klein->respond('GET', '/admin/user/status/admin', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }

    $id = isset($_GET['id']) ? (int)$_GET['id'] : null;
    if ($id !== null) {
        if ($id !== getenv('SITE_OPERATOR')) {
            $row = $db->get('user', [
                'admin',
            ], [
                'id' => $id,
            ]);
    
            $db->update('user',[
                'locked' => abs($row['admin'] - 1),
            ], [
                'id' => $id,
            ]);
      
            Helper::redirect('/admin/user/list');
        } else {
            Helper::redirect('/admin/user/list?e=protected_site_operator');
        }
    } else {
        Helper::redirect('/admin/user/list?e=missing_input');
    }
});

/**
 * Display the form for adding a new user
 */
$klein->respond('GET', '/admin/user/add', function ($request) {
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }

    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/user/add.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

/**
 * Adds a new user and optionally sends out a notification
 */
$klein->respond('POST', '/admin/user/add/save', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }

    if (isset($_POST['btn_add_user'])) {
        $_csrf_token = $_POST['_csrf_token'] ?? null;
        if ($_csrf_token !== null) {
            if ($_csrf_token !== $_SESSION['_csrf_token']) {
                Helper::redirect('/admin/user/list?e=unknown_error');
            }
            $_add =  $_POST['_add'] ?? null;
            if ($_add !== null) {
                if (!empty($_add['username']) && !empty($_add['password']) && !empty($_add['email'])) {
                    // check if username exists
                    if ($db->has('user', [
                        'username' => $_add['username'],
                    ])) {
                        if ($db->has('user', [
                            'email' => $_add['email'],
                        ])) {
                            if (!in_array($_add['sex'], array('m', 'f'))) {
                                $_add['sex'] = null;
                            }
                            $apikey = AuthHelper::generateToken(15);
                            $hash = password_hash($_add['password'], PASSWORD_BCRYPT, ['cost' => 12]);
                            
                            $db->insert('user', [
                                'username' => $_add['username'],
                                'first_name' => $_add['first_name'],
                                'last_name' => $_add['last_name'],
                                'password' => $hash,
                                'email' => $_add['email'],
                                'apikey' => $apikey,
                                'sex' => $_add['sex'],
                                'admin' => (int)$_add['admin'],
                                'locked' => (int)$_add['locked'],
                                'created_at' => date('Y-m-d H:i:s'),
                            ]);
                            
                            if ($_add['send_notification'] == 1) {
                                $params = array(
                                    'greeting' => Helper::insertValues(TranslationHelper::_t('email.new_user_notification.header', true), array(
                                        'sex' => ($_add['sex'] === 'm') ? TranslationHelper::_t('sex.male', true) : TranslationHelper::_t('sex.female', true),
                                        'last_name' => $_add['last_name'],
                                    )),
                                    'p1' => TranslationHelper::_t('email.new_user_notification.p1', true),
                                    'p2' => Helper::insertValues(TranslationHelper::_t('email.new_user_notification.p2', true), array(
                                        'username' => $_add['username'],
                                        'password' => $_add['password'],
                                    )),
                                    'p3' => TranslationHelper::_t('email.new_user_notification.p3', true),

                                );
                                
                                if (getenv('EMAIL_TRACKING_ENABLED') === 'true') {
                                    $params['tracking_token'] = Helper::generateEmailTrackingToken($_add['username'] . ' <' . $_add['email'] . '>', null);
                                }
                                
                                $body = Helper::insertValues(viewsDir() . '/email/new_user_notification.tpl.html', $params);
                                LoggerHelper::debug($body, 'info');
                                CommunicationHelper::sendMail(
                                    $body,
                                    TranslationHelper::_t('email.new_user_notification.subject', true),
                                    $_add['email'],
                                    $_add['first_name'] . ' ' . $_add['last_name'],
                                    getenv('MAILER_USER'),
                                    getenv('MAILER_USER_NAME')
                                );
                            }

                            Helper::redirect('/admin/user/list');
                        } else {
                            Helper::redirect('/admin/user/add?e=email_in_use');
                        }
                    } else {
                        Helper::redirect('/admin/user/add?e=username_in_use');
                    }
                } else {
                    Helper::redirect('/admin/user/add?e=missing_input');
                }
            } else {
                Helper::redirect('/admin/user/add?e=missing_input');
            }
        } else {
            Helper::redirect('/admin/user/add?e=unknown_error'); // invalid CSRF token
        }
    } else {
        Helper::redirect('/admin/user/add?e=unknown_error'); // wrong (or manipulated) form
    }
});

/**
 * Display the form for removing a user
 */
$klein->respond('GET', '/admin/user/remove', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    $id = $_REQUEST['id'] ?? null;
    if ($id === null) {
        Helper::redirect('/admin/user/list?e=unknown_error');
    }

    $user = Helper::getUserData($id);

    if (isset($_POST['btn_remove_user'])) {
        if (!AuthHelper::checkCSRFToken($_POST['_csrf_token'])) {
            Helper::redirect('/admin/user/list?e=unknown_error');
        }

        $db->delete('user', [
            'id' => $id,
        ]);

        Helper::redirect('/admin/user/list?e=remove_success');
    }

    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/user/remove.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});

/**
 * Display the form for editing a user
 */
$klein->respond('GET', '/admin/user/edit', function ($request) {
    $db = new DBHelper();
    if (!AuthHelper::isLoggedIn()) {
        Helper::redirect('/login');
    }
    if (!AuthHelper::isAdmin($_SESSION['user'])) {
        http_response_code(403);
        Helper::errorPage(403);
    }
    $id = $_REQUEST['id'] ?? null;
    if ($id === null) {
        Helper::redirect('/admin/user/list?e=unknown_error1');
    }

    if (isset($_POST['btn_edit_user'])) {
        if (!AuthHelper::checkCSRFToken($_POST['_csrf_token'])) {
            Helper::redirect('/admin/user/list?e=unknown_error2');
        }

        $_edit = $_POST['_edit'];
        
        $db->update('user', [
            'username' => $_edit['username'],
            'first_name' => $_edit['first_name'],
            'last_name' => $_edit['last_name'],
            'email' => $_edit['email'],
            'sex' => $_edit['sex'],
            'admin' => $_edit['admin'],
            'locked' => $_edit['locked'],
        ], [
            'id' => $id,
        ]);
        
        if (!empty($_edit['password'])) {
            $db->update('user', [
                'password' => password_hash($_edit['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            ], [
                'id' => $id,
            ]);
        }
        Helper::redirect('/admin/user/list?e=edit_successful');
    }

    $user = Helper::getUserData($id);

    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/admin/user/edit.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});