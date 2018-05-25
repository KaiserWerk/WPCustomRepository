<?php
$errors = array(
    'success_data_saved' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Your settings were successfully saved!</div>',
    'success_new_password' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> You new password was successfully set!</div>',
    'success_regenerated_apikey' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Your API key was successfully regenerated!</div>',
    'no_modification' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> No changes were made.</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Please fill in the required fields.</div>',
    'missing_password' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Please enter your password to confirm the action.</div>',
    'username_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-user"></span></strong> This username is already in use!</div>',
    'email_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> This email address is already in use!</div>',
    'passwords_not_equal' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> Passwords do not match!</div>',
    'incorrect_password' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> You entered an incorrect password!</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> An unknown error occured!</div>',
);

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Your settings</h1>
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
                <div class="col-md-6 col-sm-12">

                    <div class="card">
                        <div class="card-header">Change my password</div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php
                                AuthHelper::generateCSRFInput();
                                AuthHelper::generateHoneypotInput();
                                ?>
                                <div class="form-group">
                                    <label for="new_password1">New password:</label>
                                    <input id="new_password1" name="_edit[new_password1]" type="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password2">Repeat new password:</label>
                                    <input id="new_password2" name="_edit[new_password2]" type="password" class="form-control" required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="current_password">You current password:</label>
                                    <input id="current_password" name="_edit[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_save_new_password" class="btn btn-sm btn-primary">Save my new password</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header">My API key</div>
                        <div class="card-body">
                            <p class="text-center h3"><?php echo $user['apikey']; ?></p>
                            <form action="/user/settings" method="post">
                                <?php
                                AuthHelper::generateCSRFInput();
                                AuthHelper::generateHoneypotInput();
                                ?>
                                <br>
                                <div class="form-group">
                                    <label for="current_password">Your current password:</label>
                                    <input id="current_password" name="_edit[password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_regenerate_apikey" class="btn btn-sm btn-primary">Regenerate my API key</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">

                    <div class="card">
                        <div class="card-header">Change my data</div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php
                                AuthHelper::generateCSRFInput();
                                AuthHelper::generateHoneypotInput();
                                ?>
                                <div class="form-group">
                                    <label for="username">Username:</label>
                                    <input id="username" name="_edit[username]" type="text" class="form-control" placeholder="<?php echo $user['username']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="first_name">First name:</label>
                                    <input id="first_name" name="_edit[first_name]" type="text" class="form-control" placeholder="<?php echo $user['first_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name">Last name:</label>
                                    <input id="last_name" name="_edit[last_name]" type="text" class="form-control" placeholder="<?php echo $user['last_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input id="email" name="_edit[email]" type="text" class="form-control" placeholder="<?php echo $user['email']; ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="current_password">Your current password:</label>
                                    <input id="current_password" name="_edit[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_save_my_data" class="btn btn-sm btn-primary">Save my data</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header">Lock my account</div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php
                                AuthHelper::generateCSRFInput();
                                AuthHelper::generateHoneypotInput();
                                ?>
                                <div class="form-group">
                                    <label for="locked" class="text-center" style="display: block;">
                                        <input id="locked" name="_lock[enabled]" type="checkbox" value="1" required>
                                        Yes, lock my account
                                    </label>
                                </div>

                                <div class="form-group">
                                    <label for="current_password">Your current password:</label>
                                    <input id="current_password" name="_lock[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_lock_account" class="btn btn-sm btn-primary">Lock my account</button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


