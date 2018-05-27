<?php
$errors = array(
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Please enter your username and password.</div>',
    'incorrect_credentials' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> Username/Password incorrect!</div>',
    'account_locked' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> Your account is currenty locked, you cannot log in.</div>',
    'too_many_attempts' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> You tried too many times to log in, so your account has been locked.</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> An unknown error occured!</div>',
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
                        Username / Email: <input type="text" name="_login[username]" class="form-control"><br>
                        Password: <input type="password" name="_login[password]" class="form-control"><br>
                        <input type="submit" value="Login" class="btn btn-primary">
                    </form>
                </div>
                <div class="col-md-6">
                    You forgot your password? Click <a href="/resetting/request">here</a>.
                </div>
            </div>
            
        </div>
    </div>

