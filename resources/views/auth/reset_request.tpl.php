<?php
$errors = array(
    'success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> '.Trans::_t('error.success', true).'</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.missing_input.reset_request', true).'</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.unknown_error', true).'</div>',
);
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <h1 class="mt-5"><?php Trans::_t('site_caption.reset_request.request'); ?></h1>
            <?php
            if (isset($_GET['e'])) {
                echo $errors[$_GET['e']];
            }
            ?>
            <br>
            <form method="post">
                <input type="hidden" name="_csrf_token" value="<?php echo $_SESSION['_csrf_token']; ?>">
                <div class="form-group">
                    <label for="username"><?php Trans::_t('password_reset.request_form.username'); ?>:</label>
                    <input type="text" name="_reset_request[username]" class="form-control" id="username">
                </div>
                <button type="submit" name="btn_reset_request" class="btn btn-primary"><?php Trans::_t('password_reset.request_form.request'); ?></button>
            </form>

        </div>
        <div class="col-md-5 col-sm-12">
            <h1 class="mt-5"><?php Trans::_t('site_caption.reset_request.info'); ?></h1>
            <br>
            <?php Trans::_t('password_reset.request_form.info'); ?>
        </div>
    </div>
</div>


