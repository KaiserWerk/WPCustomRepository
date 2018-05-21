<?php
$errors = array(
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> '.Trans::_t('error.missing_input.login', true).'</div>',
    'incorrect_credentials' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.incorrect_credentials', true).'</div>',
    'account_locked' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> '.Trans::_t('error.account_locked', true).'</div>',
    'too_many_attempts' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> '.Trans::_t('error.too_many_attempts', true).'</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> '.Trans::_t('error.unknown_error', true).'</div>',
);
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Login</h1>
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
                <div class="col-md-6">
                    <form method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>
                        Username / Email: <input type="text" name="_login[username]" class="form-control" value="admin"><br>
                        Password: <input type="password" name="_login[password]" class="form-control" value="test"><br>
                        <input type="submit" value="Login" class="btn btn-primary">
                    </form>
                </div>
                <div class="col-md-6">
                    fff
                </div>
            </div>
            
        </div>
    </div>

