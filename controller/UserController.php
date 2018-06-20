<?php

/**
 * Displays forms for changing various user setting, like email
 * or password
 */
$router->respond(['GET', 'POST'], '/user/settings', function ($request) {
    $db = new DBHelper();
    
    AuthHelper::requireLogin();
    $user = Helper::getUserData($_SESSION['user']);
    

    // change data
    if (isset($_POST['btn_save_my_data'])) {
        AuthHelper::requireValidCSRFToken();
        AuthHelper::requireValidHonepot();
        

        $_edit = $_POST['_edit'] ?? null;

        // check password
        if (!password_verify($_edit['current_password'], $user['password'])) {
            Helper::setMessage('The password you entered was not correct!', 'danger');
            Helper::redirect('/user/settings');
        }

        $changed = 0;
        if (!empty($_edit['username']) && $_edit['username'] !== $user['username']) {
            if (!Helper::isUsernameInUse($_edit['username'])) {
                $db->update('user', [
                    'username' => $_edit['username'],
                ], [
                    'id' => $user['id']
                ]);
                $changed++;
                CommunicationHelper::sendNotification('User ' . $user['username'] . ' has changed his username to ' . $_edit['username']);
            } else {
                Helper::setMessage('This username is already in use!', 'danger');
                Helper::redirect('/user/settings');
            }
        }

        if (!empty($_edit['email']) && $_edit['email'] !== $user['email']) {
            if (!Helper::isEmailInUse($_edit['email'])) {
                $db->update('user', [
                    'email' => $_edit['email'],
                ], [
                    'id' => $user['id']
                ]);
                $changed++;
                CommunicationHelper::sendNotification('User ' . $user['username'] . ' has changed his e-mail address to ' . $_edit['email']);
            } else {
                Helper::setMessage('This email address is already in use!', 'danger');
                Helper::redirect('/user/settings');
            }
        }

        if (!empty($_edit['first_name']) && $_edit['first_name'] !== $user['first_name']) {
            $db->update('user', [
                'first_name' => $_edit['first_name'],
            ], [
                'id' => $user['id']
            ]);
            $changed++;
            CommunicationHelper::sendNotification('User ' . $user['username'] . ' has changed his first name to ' . $_edit['first_name']);
        }

        if (!empty($_edit['last_name']) && $_edit['last_name'] !== $user['last_name']) {
            $db->update('user', [
                'last_name' => $_edit['last_name'],
            ], [
                'id' => $user['id']
            ]);
            $changed++;
            CommunicationHelper::sendNotification('User ' . $user['username'] . ' has changed his last name to ' . $_edit['last_name']);
        }

        if ($changed > 0) {
            Helper::setMessage('Changes saved!', 'success');
            Helper::redirect('/user/settings');
        }
        Helper::setMessage('No changes were made.');
        Helper::redirect('/user/settings');

    }

    /**
     * deliberately lock my account
     */
    if (isset($_POST['btn_lock_account'])) {
        AuthHelper::requireValidCSRFToken();

        $_lock = $_POST['_lock'] ?? null;

        // check password
        if (!password_verify($_lock['current_password'], $user['password'])) {
            Helper::setMessage('An unknown error occured!', 'danger');
            Helper::redirect('/user/settings');
        }

        if (isset($_lock['enabled']) && $_lock['enabled'] === '1') {
            $db->update('user', [
                'locked' => 1,
            ], [
                'id' => $user['id']
            ]);
            CommunicationHelper::sendNotification('User ' . $user['username'] . ' has deliberately locked his account!');
            Helper::setMessage('You locked your account and are now logged out.');
            Helper::redirect('/logout');
        } else {
            Helper::setMessage('Please fill in all required fields!', 'warning');
            Helper::redirect('/user/settings');
        }
    }

    /**
     * set new password
     */
    if (isset($_POST['btn_save_new_password'])) {
        AuthHelper::requireValidCSRFToken();

        $_edit = $_POST['_edit'] ?? null;
        if ($_edit !== null &&
            !empty($_edit['new_password1']) &&
            !empty($_edit['new_password2']) &&
            !empty($_edit['current_password'])
        ) {

            if (password_verify($_edit['current_password'], $user['password'])) {
                if ($_edit['new_password1'] === $_edit['new_password2']) {
                    $newHash = password_hash($_edit['new_password1'], PASSWORD_BCRYPT, array('cost' => 12));
                    $db->update('user', [
                        'password' => $newHash,
                    ], [
                        'id' => $user['id']
                    ]);
                    CommunicationHelper::sendNotification('User ' . $user['username'] . ' has changed his password.');
                    Helper::setMessage('You set a new password!', 'success');
                    Helper::redirect('/user/settings');
                } else {
                    Helper::setMessage('Passwords do not match!', 'danger');
                    Helper::redirect('/user/settings');
                }
            } else {
                Helper::setMessage('You entered an incorrect password!', 'danger');
                Helper::redirect('/user/settings');
            }
        } else {
            Helper::setMessage('Please enter your current password when changing your data!', 'warning');
            Helper::redirect('/user/settings');
        }
    }

    /**
     * regenerate API key
     */
    if (isset($_POST['btn_regenerate_apikey'])) {
        AuthHelper::requireValidCSRFToken();

        $_edit = $_POST['_edit'] ?? null;
        if ($_edit !== null || empty($_edit['password'])) {
            if (password_verify($_edit['password'], $user['password'])) {
                $db->update('user', [
                    'apikey' => AuthHelper::generateToken(20),
                ], [
                    'id' => $user['id']
                ]);
                CommunicationHelper::sendNotification('User ' . $user['username'] . ' has regenerated his API key.');
                Helper::setMessage('Your API was regenerated!', 'success');
                Helper::redirect('/user/settings');
            } else {
                Helper::setMessage('The password you entered was incorrect!!', 'danger');
                Helper::redirect('/user/settings');
            }
        } else {
            Helper::setMessage('Please enter your current password!', 'warning');
            Helper::redirect('/user/settings');
        }
    }
    
    Helper::renderPage('/user/settings.tpl.php', [
        'user' => $user,
    ]);
});