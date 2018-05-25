<?php
$errors = array(
    'success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Password was successfully reset!</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Please fill in all required fields.</div>',
    'password_too_short' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-lock"></span></strong> The password you entered was too short.</div>',
    'invalid_token' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> You have used an invalid token.</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> An unknown error occured!</div>',
    'passwords_not_identical' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> The passwords you entered did not match!</div>',
);
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <h1 class="mt-5">Reset your password</h1>
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
            <form method="post" action="?_confirmation_token=<?php echo $token; ?>">
                <?php
                AuthHelper::generateCSRFInput();
                AuthHelper::generateHoneypotInput();
                if (!isset($_GET['confirmation_token'])) {
                    ?>
                    <div class="form-group">
                        <label for="password1">Confirmation Token:</label>
                        <input type="text" name="confirmation_token" class="form-control" id="password1">
                    </div>
                    <?php
                }
                ?>
                <div class="form-group">
                    <label for="password1">New Password:</label>
                    <input type="password" name="_reset_reset[password1]" class="form-control" id="password1">
                </div>
                <div class="form-group">
                    <label for="password2">Repeat New Password:</label>
                    <input type="password" name="_reset_reset[password2]" class="form-control" id="password2">
                </div>
                <button type="submit" name="btn_reset_reset" class="btn btn-primary">Set New Password</button>
            </form>

        </div>
        <div class="col-md-5 col-sm-12">
            <h1 class="mt-5">Info</h1>
            <br>
            <p>
                <ul>
                    <li>Password minimum length: 12 characters</li>
                    <li>At least 1 uppercase and 1 lowercase character</li>
                    <li>At least 1 numerical character</li>
                </ul>
            </p>
        </div>
    </div>
</div>


