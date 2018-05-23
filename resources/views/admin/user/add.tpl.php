<?php
$errors = array(
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.TranslationHelper::_t('error.missing_input.login', true).'</div>',
    'username_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-user"></span></strong> '.TranslationHelper::_t('error.username_in_use', true).'</div>',
    'email_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> '.TranslationHelper::_t('error.email_in_use', true).'</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.TranslationHelper::_t('error.unknown_error', true).'</div>',
);

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Add User</h1>
            <?php
            if (isset($_GET['e'])) {
                $e = $_GET['e'];
                if (!array_key_exists($e, $errors)) {
                    $e = 'unknown_error';
                }
                echo $errors[ $e ];
            }
            ?>
            <br>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <form action="/admin/user/add/save" method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input id="username" name="_add[username]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First name:</label>
                            <input id="first_name" name="_add[first_name]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name:</label>
                            <input id="last_name" name="_add[last_name]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input id="password" name="_add[password]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input id="email" name="_add[email]" type="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Sex:</label><br>
                            <label class="radio-inline"><input name="_add[sex]" type="radio" value="m"> Male</label> &nbsp;
                            <label class="radio-inline"><input name="_add[sex]" type="radio" value="f" checked> Female</label> &nbsp;
                        </div>
                        <div class="checkbox">
                            Flags:<br>
                            <label for="locked"><input id="locked" name="_add[locked]" type="checkbox" value="1"> Locked</label> &nbsp;
                            <label for="admin"><input id="admin" name="_add[admin]" type="checkbox" value="1"> Admin</label> &nbsp;
                            <label for="send_notification"><input id="send_notification" name="_add[send_notification]" type="checkbox" value="1" checked> Send Notification</label> &nbsp;
                        </div>
                        <div class="form-group">
                            <br>
                            <button type="submit" name="btn_add_user" class="btn btn-primary">Add user</button>
                            <a href="/admin/user/list" class="btn">Back to list</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-sm-12">
                    <br>
                    Passwords must be at least 12 characters long and contain a mix of letters, numbers and special chars.
                </div>
            </div>

        </div>
    </div>
</div>


