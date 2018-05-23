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
            <h1 class="mt-5">Remove User</h1>
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

                <div class="col-md-12 col-sm-12 text-center">
                    <form action="/admin/user/remove?id=<?php echo $id; ?>" method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>

                        <p class="h5">Are you sure you want to permanently remove the account <?php echo $user['username']; ?>?</p>

                        <div class="form-group">
                            <br>
                            <button type="submit" name="btn_remove_user" class="btn btn-lg btn-danger">Remove user</button>
                            <a href="/admin/user/list" class="btn btn-lg btn-light">Back to list</a>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>