<?php
$errors = array(
    'add_success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Added successfully!</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Missing input!</div>',
    'license_exists' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> This license already exists. Please execute a renewal!</div>',
    'key_in_use' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> This license key is already in use!</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> Unknown error!</div>',
);

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-5">Add License</h1>
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
                    <label for="license_user">License User</label>
                    <input type="text" name="_add[license_user]" id="license_user" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="license_key">License Key</label>
                    <textarea name="_add[license_key]" id="license_key" class="form-control" rows="4" required><?php echo $key; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="license_host">License Host</label>
                    <input type="text" name="_add[license_host]" id="license_host" class="form-control">
                </div>
                <div class="form-group">
                    <label for="plugin_slug">Plugin Slug</label>
                    <input type="text" name="_add[plugin_slug]" id="license_host" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="valid_until">Valid Until</label>
                    <input type="text" name="_add[valid_until]" id="valid_until" class="form-control" required value="<?php echo date('Y')+1 . '-' . date('m-d'); ?>">
                </div>
                <div class="form-group">
                    <br>
                    <button class="btn btn-success" name="license_button">Add New License</button>
                </div>
            </form>
        </div>
    </div>
</div>
