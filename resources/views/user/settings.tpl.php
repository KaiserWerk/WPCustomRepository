<?php
$errors = array(
    'success_data_saved' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> '.Trans::_t('error.success_data_saved', true).'</div>',
    'success_new_password' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> '.Trans::_t('error.success_new_password', true).'</div>',
    'success_regenerated_apikey' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> '.Trans::_t('error.success_regenerated_apikey', true).'</div>',
    'no_modification' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.no_modification', true).'</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.missing_input', true).'</div>',
    'missing_password' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.missing_password', true).'</div>',
    'username_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-user"></span></strong> '.Trans::_t('error.username_in_use', true).'</div>',
    'email_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> '.Trans::_t('error.email_in_use', true).'</div>',
    'passwords_not_equal' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> '.Trans::_t('error.passwords_not_equal', true).'</div>',
    'incorrect_password' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.incorrect_password', true).'</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.unknown_error', true).'</div>',
);

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5"><?php Trans::_t('user_settings.main_header'); ?></h1>
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
                        <div class="card-header"><?php TranslationHelper::_t('user_settings.header2'); ?></div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php Auth::generateCSRFInput(); ?>
                                <div class="form-group">
                                    <label for="new_password1"><?php Trans::_t('user_settings.new_password'); ?>:</label>
                                    <input id="new_password1" name="_edit[new_password1]" type="password" class="form-control" required>
                                </div>
                                <div class="form-group">
                                    <label for="new_password2"><?php Trans::_t('user_settings.new_password_repeat'); ?>:</label>
                                    <input id="new_password2" name="_edit[new_password2]" type="password" class="form-control" required>
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="current_password"><?php Trans::_t('user_settings.current_password'); ?>:</label>
                                    <input id="current_password" name="_edit[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_save_new_password" class="btn btn-sm btn-primary"><?php Trans::_t('user_settings.btn_save_new_password'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header"><?php TranslationHelper::_t('user_settings.header3'); ?></div>
                        <div class="card-body">
                            <p class="text-center h3"><?php echo $user['apikey']; ?></p>
                            <form action="/user/settings" method="post">
                                <?php Auth::generateCSRFInput(); ?>
                                <br>
                                <div class="form-group">
                                    <label for="current_password"><?php Trans::_t('user_settings.current_password'); ?>:</label>
                                    <input id="current_password" name="_edit[password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_regenerate_apikey" class="btn btn-sm btn-primary"><?php Trans::_t('user_settings.btn_regenerate_apikey'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
                <div class="col-md-6 col-sm-12">

                    <div class="card">
                        <div class="card-header"><?php TranslationHelper::_t('user_settings.header1'); ?></div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php Auth::generateCSRFInput(); ?>
                                <div class="form-group">
                                    <label for="username"><?php Trans::_t('user_settings.username'); ?>:</label>
                                    <input id="username" name="_edit[username]" type="text" class="form-control" placeholder="<?php echo $user['username']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="first_name"><?php Trans::_t('user_settings.first_name'); ?>:</label>
                                    <input id="first_name" name="_edit[first_name]" type="text" class="form-control" placeholder="<?php echo $user['first_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="last_name"><?php Trans::_t('user_settings.last_name'); ?>:</label>
                                    <input id="last_name" name="_edit[last_name]" type="text" class="form-control" placeholder="<?php echo $user['last_name']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email"><?php Trans::_t('user_settings.email'); ?>:</label>
                                    <input id="email" name="_edit[email]" type="text" class="form-control" placeholder="<?php echo $user['email']; ?>">
                                </div>
                                <br>
                                <div class="form-group">
                                    <label for="current_password"><?php Trans::_t('user_settings.current_password'); ?>:</label>
                                    <input id="current_password" name="_edit[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_save_my_data" class="btn btn-sm btn-primary"><?php Trans::_t('user_settings.btn_save_my_data'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <br>

                    <div class="card">
                        <div class="card-header"><?php TranslationHelper::_t('user_settings.header4'); ?></div>
                        <div class="card-body">
                            <form action="/user/settings" method="post">
                                <?php Auth::generateCSRFInput(); ?>
                                <div class="form-group">
                                    <label for="locked" class="text-center" style="display: block;"><input id="locked" name="_lock[enabled]" type="checkbox" value="1" required> <?php Trans::_t('user_settings.lock_account'); ?></label>
                                </div>

                                <div class="form-group">
                                    <label for="current_password"><?php Trans::_t('user_settings.current_password'); ?>:</label>
                                    <input id="current_password" name="_lock[current_password]" type="password" class="form-control" required>
                                </div>

                                <div class="form-group">
                                    <button type="submit" name="btn_lock_account" class="btn btn-sm btn-primary"><?php Trans::_t('user_settings.btn_lock_account'); ?></button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>


