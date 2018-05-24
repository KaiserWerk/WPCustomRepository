<?php

/**
 * Displays forms for changing various user setting, like email
 * or password
 */
$klein->respond(['GET', 'POST'], '/user/settings', function ($request) {
    $db = new DBHelper();

    if (AuthHelper::isLoggedIn()) {
        $user = Helper::getUserData($_SESSION['user']);
    } else {
        Helper::redirect('/login');
    }

    // change data
    if (isset($_POST['btn_save_my_data'])) {
        if (AuthHelper::checkCSRFToken() === false) {
            Helper::redirect('/user/settings?e=unknown_error');
        }

        $_edit = $_POST['_edit'] ?? null;

        // check password
        if (!password_verify($_edit['current_password'], $user['password'])) {
            Helper::redirect('/user/settings?e=incorrect_password');
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
                Helper::redirect('/user/settings?e=username_in_use');
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
                Helper::redirect('/user/settings?e=email_in_use');
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
            Helper::redirect('/user/settings?e=success_data_saved');
        }
        Helper::redirect('/user/settings?e=no_modification');

    }

    /**
     * deliberately lock my account
     */
    if (isset($_POST['btn_lock_account'])) {
        if (AuthHelper::checkCSRFToken() === false) {
            Helper::redirect('/user/settings?e=unknown_error');
        }

        $_lock = $_POST['_lock'] ?? null;

        // check password
        if (!password_verify($_lock['current_password'], $user['password'])) {
            Helper::redirect('/user/settings?e=incorrect_password');
        }

        if (isset($_lock['enabled']) && $_lock['enabled'] === "1") {
            $db->update('user', [
                'locked' => 1,
            ], [
                'id' => $user['id']
            ]);
            CommunicationHelper::sendNotification('User ' . $user['username'] . ' has deliberately locked his account!');
            Helper::redirect('/logout');
        } else {
            Helper::redirect('/user/settings?e=missing_input');
        }
    }

    /**
     * set new password
     */
    if (isset($_POST['btn_save_new_password'])) {
        if (AuthHelper::checkCSRFToken() === false) {
            Helper::redirect('/user/settings?e=unknown_error');
        }

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
                    Helper::redirect('/user/settings?e=success_new_password');
                } else {
                    Helper::redirect('/user/settings?e=passwords_not_equal');
                }
            } else {
                Helper::redirect('/user/settings?e=incorrect_password');
            }
        } else {
            Helper::redirect('/user/settings?e=missing_input');
        }
    }

    /**
     * regenerate API key
     */
    if (isset($_POST['btn_regenerate_apikey'])) {
        if (AuthHelper::checkCSRFToken() === false) {
            Helper::redirect('/user/settings?e=unknown_error');
        }

        $_edit = $_POST['_edit'] ?? null;
        if ($_edit !== null || empty($_edit['password'])) {
            if (password_verify($_edit['password'], $user['password'])) {
                $db->update('user', [
                    'apikey' => AuthHelper::generateToken(20),
                ], [
                    'id' => $user['id']
                ]);
                CommunicationHelper::sendNotification('User ' . $user['username'] . ' has regenerated his API key.');
                Helper::redirect('/user/settings?e=success_regenerated_apikey');
            } else {
                Helper::redirect('/user/settings?e=incorrect_password');
            }
        } else {
            Helper::redirect('/user/settings?e=missing_input');
        }
    }
    
    require_once viewsDir() . '/header.tpl.php';
    require_once viewsDir() . '/user/settings.tpl.php';
    require_once viewsDir() . '/footer.tpl.php';
});