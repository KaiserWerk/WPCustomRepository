<?php
$errors = array(
    'success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> A mail with intructions was successfully sent.</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Please fill in your username or email address.</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> An unknown error occured.</div>',
);
?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <h1 class="mt-5">Info</h1>
            <br>
            <p>
                In case you forgot your password, can request a new one here by supplying your username or
                email address. An email with instructions will be sent to your inbox.
            </p>
        </div>
        <div class="col-md-6 col-sm-12">
            <h1 class="mt-5">Request a new password</h1>
            <?php
            if (isset($_GET['e'])) {
                $e = $_GET['e'];
                if (!array_key_exists($e, $errors)) {
                    $e = 'unknown_error';
                }
                echo $errors[ $e ];
            }
            ?>
            <form method="post">
                <?php
                AuthHelper::generateCSRFInput();
                AuthHelper::generateHoneypotInput();
                ?>
                <div class="form-group">
                    <label for="username">Username or Email:</label>
                    <input type="text" name="_reset_request[username]" class="form-control" id="username">
                </div>
                <button type="submit" name="btn_reset_request" class="btn btn-primary">Send the request</button>
            </form>

        </div>
        
    </div>
</div>


