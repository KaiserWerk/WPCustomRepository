<?php
$errors = array(
    'success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> '.Trans::_t('error.success.reset_reset', true).'</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.missing_input.reset_reset', true).'</div>',
    'password_too_short' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> '.Trans::_t('error.password_too_short', true).'</div>',
    'invalid_token' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.invalid_token', true).'</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.unknown_error', true).'</div>',
    'passwords_not_identical' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.passwords_not_equal', true).'</div>',
);
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <h1 class="mt-5"><?php Trans::_t('site_caption.reset_request.reset'); ?></h1>
            <?php
            if (isset($_GET['e'])) {
                echo $errors[$_GET['e']];
            }
            ?>
            <br>
            <form method="post" action="?_confirmation_token=<?php echo $token; ?>">
                <input type="hidden" name="_csrf_token" value="<?php echo $_SESSION['_csrf_token']; ?>">
                <?php
                if (!isset($_GET['confirmation_token'])) {
                    ?>
                    <div class="form-group">
                        <label for="password1"><?php Trans::_t('password_reset.reset_form.token'); ?>:</label>
                        <input type="text" name="confirmation_token" class="form-control" id="password1">
                    </div>
                    <?php
                }
                ?>
                <div class="form-group">
                    <label for="password1"><?php Trans::_t('password_reset.reset_form.password'); ?>:</label>
                    <input type="password" name="_reset_reset[password1]" class="form-control" id="password1">
                </div>
                <div class="form-group">
                    <label for="password2"><?php Trans::_t('password_reset.reset_form.password_repeat'); ?>:</label>
                    <input type="password" name="_reset_reset[password2]" class="form-control" id="password2">
                </div>
                <button type="submit" name="btn_reset_reset" class="btn btn-primary"><?php Trans::_t('password_reset.reset_form.reset'); ?></button>
            </form>

        </div>
        <div class="col-md-5 col-sm-12">
            <h1 class="mt-5"><?php Trans::_t('site_caption.reset_request.info'); ?></h1>
            <br>
            <?php Trans::_t('password_reset.reset_form.info'); ?>
        </div>
    </div>
</div>


