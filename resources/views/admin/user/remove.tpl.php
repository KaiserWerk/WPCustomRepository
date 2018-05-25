<?php
$errors = array(
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Missing input!</div>',
    'username_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-user"></span></strong> This username is already in use!</div>',
    'email_in_use' => '<br>
            <div class="alert alert-warning alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-envelope"></span></strong> This email is already in use!</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> Unknown error!</div>',
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
                    <form action="/admin/user/<?php echo $id; ?>/remove" method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>

                        <p class="h5">Are you sure you want to permanently remove the following user account? <br><br><?php echo $user['username']; ?></p>

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