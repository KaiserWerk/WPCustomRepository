<?php

$router->with('/admin/user', function () use ($router) {
    
    /**
     * List all users
     */
    $router->respond('GET', '/list', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        $db = new DBHelper();
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
        
        Helper::renderPage('/admin/user/list.tpl.php', [
            'users' => $users,
        ]);
    });
    
    /**
     * Change the 'locked' status of a user
     */
    $router->respond('GET', '/[:id]/status/locked', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $db = new DBHelper();
        $id = $request->id;
        if ($id !== null) {
            if ($id !== (int)getenv('SITE_OPERATOR')) {
                $row = $db->get('user', [
                    'locked',
                ], [
                    'id' => $id,
                ]);
                
                if ($row === false) {
                    die('This user entry does not exist');
                }
        
                $db->update('user', [
                    'locked' => abs((int)$row['locked'] - 1),
                ], [
                    'id' => $id,
                ]);
        
                Helper::setMessage('Lock status changed.');
                Helper::redirect('/admin/user/list');
            } else {
                Helper::setMessage('You cannot edit the site operator!', 'danger');
                Helper::redirect('/admin/user/list');
            }
        }
    });
    
    /**
     * Change the admin status of a user
     * (not of oneself)
     */
    $router->respond('GET', '/[:id]/status/admin', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $db = new DBHelper();
        $id = $request->id;
        if ($id !== null) {
            if ($id !== (int)getenv('SITE_OPERATOR')) {
                $row = $db->get('user', [
                    'admin',
                ], [
                    'id' => $id,
                ]);
                
                if ($row === false) {
                    die('This user entry does not exist');
                }
                
                $db->update('user',[
                    'admin' => abs((int)$row['admin'] - 1),
                ], [
                    'id' => $id,
                ]);
            
                Helper::setMessage('Status changed!', 'success');
                Helper::redirect('/admin/user/list');
            } else {
                Helper::setMessage('You cannot edit the site operator!', 'danger');
                Helper::redirect('/admin/user/list');
            }
        }
    });
    
    /**
     * Adds a new user and optionally sends out a notification
     */
    $router->respond(['GET', 'POST'], '/add', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        $db = new DBHelper();
    
        if (isset($_POST['btn_add_user'])) {
            $_csrf_token = $_POST['_csrf_token'] ?? null;
            if ($_csrf_token !== null) {
                if ($_csrf_token !== $_SESSION['_csrf_token']) {
                    Helper::setMessage('Unknown error.', 'danger');
                    Helper::redirect('/admin/user/list');
                }
                $_add =  $_POST['_add'] ?? null;
                if ($_add !== null) {
                    if (!empty($_add['username']) && !empty($_add['password']) && !empty($_add['email'])) {
                        // check if username exists
                        if (!$db->has('user', [
                            'username' => $_add['username'],
                        ])) {
                            if (!$db->has('user', [
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
                                    'admin' => $_add['admin'] ?? 0,
                                    'locked' => $_add['locked'] ?? 0,
                                    'created_at' => date('Y-m-d H:i:s'),
                                ]);
                                
                                
                                if ($_add['send_notification'] == 1) {
                                    $params = array(
                                        'greeting' => Helper::insertValues('Hey {first_name},', array(
                                            'first_name' => $_add['first_name'],
                                        )),
                                        'p1' => 'a user account has just been created for you. From now on please login using the following credentials:',
                                        'p2' => Helper::insertValues('<ul><li>Username: {username}</li><li>Password: {password}</li></ul>', array(
                                            'username' => $_add['username'],
                                            'password' => $_add['password'],
                                        )),
                                        'p3' => 'There is the ',
    
                                    );
                                    
                                    if (getenv('EMAIL_TRACKING_ENABLED') === 'true') {
                                        $params['tracking_token'] = Helper::generateEmailTrackingToken($_add['username'] . ' <' . $_add['email'] . '>', null);
                                    }
        
                                    $body = Helper::insertValues(viewsDir() . '/email/new_user_notification.tpl.html', $params);
                                    CommunicationHelper::sendMail(
                                        $body,
                                        'WPCustomRepository - Your new user account',
                                        $_add['email'],
                                        $_add['first_name'] . ' ' . $_add['last_name'],
                                        getenv('MAILER_USER'),
                                        getenv('MAILER_USER_NAME')
                                    );
                                    
                                }
        
                                Helper::setMessage('User added!', 'success');
                                Helper::redirect('/admin/user/list');
                            } else {
                                Helper::setMessage('This email is already in use!', 'warning');
                                Helper::redirect('/admin/user/add');
                            }
                        } else {
                            Helper::setMessage('This username is already in use!', 'warning');
                            Helper::redirect('/admin/user/add');
                        }
                    } else {
                        Helper::setMessage('Missing input!');
                        Helper::redirect('/admin/user/add');
                    }
                } else {
                    Helper::setMessage('Missing input!');
                    Helper::redirect('/admin/user/add');
                }
            } else {
                Helper::setMessage('Unknown error.', 'danger');
                Helper::redirect('/admin/user/add'); // invalid CSRF token
            }
        } else {
            Helper::renderPage('/admin/user/add.tpl.php');
        }
    });
    
    /**
     * Display the form for removing a user
     */
    $router->respond(['GET', 'POST'], '/[:id]/remove', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $id = $request->id;
        if ($id === null) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/admin/user/list');
        }
    
        if ((int)$id === (int)getenv('SITE_OPERATOR')) {
            Helper::setMessage('You cannot edit the site operator!', 'danger');
            Helper::redirect('/admin/user/list');
        }
        
        if (isset($_POST['btn_remove_user'])) {
            AuthHelper::requireValidCSRFToken();
            
            $db = new DBHelper();
            $db->delete('user', [
                'id' => $id,
            ]);
        
            Helper::setMessage('User removed!', 'success');
            Helper::redirect('/admin/user/list');
        } else {
            $user = Helper::getUserData($id);
            
            Helper::renderPage('/admin/user/remove.tpl.php', [
                'id' => $id,
                'user' => $user,
            ]);
        }
    });
    
    /**
     * Display the form for editing a user
     */
    $router->respond(['GET', 'POST'], '/[:id]/edit', function ($request) {
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
        
        $id = $request->id;
        if ($id === null) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/admin/user/list');
        }
        
        if (isset($_POST['btn_edit_user'])) {
            AuthHelper::requireValidCSRFToken();
            
            $_edit = $_POST['_edit'];
            
            if ($id === (int)getenv('SITE_OPERATOR')) {
                $fields = [
                    'first_name' => $_edit['first_name'],
                    'last_name' => $_edit['last_name'],
                    'sex' => $_edit['sex'],
                ];
            } else {
                $fields = [
                    'username' => $_edit['username'],
                    'first_name' => $_edit['first_name'],
                    'last_name' => $_edit['last_name'],
                    'email' => $_edit['email'],
                    'sex' => $_edit['sex'],
                    'admin' => $_edit['admin'],
                    'locked' => $_edit['locked'],
                ];
        
                if (!empty($_edit['password'])) {
                    $fields['password'] = password_hash($_edit['password'], PASSWORD_BCRYPT, ['cost' => 12]);
                }
            }
            
            $db = new DBHelper();
            $db->update('user', $fields, [
                'id' => $id,
            ]);
        
            Helper::setMessage('Changes saved!', 'success');
            Helper::redirect('/admin/user/list');
        } else {
            $user = Helper::getUserData($id);
            
            Helper::renderPage('/admin/user/edit.tpl.php', [
                'id' => $id,
                'user' => $user,
            ]);
        }
    });
    
});