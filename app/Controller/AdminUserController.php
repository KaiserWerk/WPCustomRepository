<?php

class AdminUserController extends Controller
{
    /**
     * @Route("/admin/user/list", name="admin_user_list")
     * @Method(["GET"])
     */
    public function adminUserListAction()
    {
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
    
        TemplateHelper::renderHTML('/admin/user/list.tpl.php', [
            'users' => $users,
        ]);
    }
    
    /**
     * @Route("/admin/user/[:id]/status/locked", name="admin_user_status_locked")
     * @Method(["GET"])
     */
    public function adminUserStatusLockedAction($params)
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        $db = new DBHelper();
        $id = $params->id;
        if ($id !== null) {
            if ($id !== $config['site']['operator']) {
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
    }
    
    /**
     * @Route("/admin/user/[:id]/status/admin", name="admin_user_status_admin")
     * @Method(["GET"])
     */
    public function adminUserStatusAdminAction($params)
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        $db = new DBHelper();
        $id = $params->id;
        if ($id !== null) {
            if ($id !== $config['site']['operator']) {
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
    }
    
    /**
     * @Route("/admin/user/add", name="admin_user_add")
     * @Method(["GET", "POST"])
     */
    public function adminUserAddAction()
    {
        global $config;
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
                                $hash = password_hash($_add['password'], PASSWORD_BCRYPT, ['cost' => 12]);
                            
                                $db->insert('user', [
                                    'username' => $_add['username'],
                                    'first_name' => $_add['first_name'],
                                    'last_name' => $_add['last_name'],
                                    'password' => $hash,
                                    'email' => $_add['email'],
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
                                
                                    if ($config['site']['email_tracking'] === true) {
                                        $params['tracking_token'] = Helper::generateEmailTrackingToken($_add['username'] . ' <' . $_add['email'] . '>', null);
                                    }
                                
                                    $body = Helper::insertValues(viewsDir() . '/email/new_user_notification.tpl.html', $params);
                                    Helper::sendMail(
                                        $body,
                                        'WPCustomRepository - Your new user account',
                                        $_add['email'],
                                        $_add['first_name'] . ' ' . $_add['last_name'],
                                        $config['mailer']['username'],
                                        $config['mailer']['fullname']
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
            TemplateHelper::renderHTML('/admin/user/add');
        }
    }
    
    /**
     * @Route("/admin/user/[:id]/remove", name="admin_user_remove")
     * @Method(["GET", "POST"])
     */
    public function adminUserRemoveAction($params)
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        $id = $params->id;
        if ($id === null) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/admin/user/list');
        }
    
        if ((int)$id === $config['site']['operator']) {
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
        
            TemplateHelper::renderHTML('/admin/user/remove.tpl.php', [
                'id' => $id,
                'user' => $user,
            ]);
        }
    }
    
    /**
     * @Route("/admin/user/[:id]/edit", name="admin_user_edit")
     * @Method(["GET", "POST"])
     */
    public function adminUserEditAction($params)
    {
        global $config;
        AuthHelper::init();
        AuthHelper::requireLogin();
        AuthHelper::requireAdmin();
    
        $id = $params->id;
        if ($id === null) {
            Helper::setMessage('Unknown error!', 'danger');
            Helper::redirect('/admin/user/list');
        }
    
        if (isset($_POST['btn_edit_user'])) {
            AuthHelper::requireValidCSRFToken();
        
            $_edit = $_POST['_edit'];
        
            if ($id === $config['site']['operator']) {
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
        
            TemplateHelper::renderHTML('/admin/user/edit.tpl.php', [
                'id' => $id,
                'user' => $user,
            ]);
        }
    }
}
