<?php
$errors = array(
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Missing input!</div>',
    'license_exists' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> This license already exists. Please do a renewal!</div>',
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
            <h1 class="mt-5">Edit License</h1>
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
                    <label for="license_user">License User*</label>
                    <input type="text" name="_edit[license_user]" id="license_user" class="form-control" required value="<?php echo $license['license_user']; ?>">
                </div>
                <div class="form-group">
                    <label for="license_key">License Key*</label>
                    <textarea name="_edit[license_key]" id="license_key" class="form-control" maxlength="200" rows="4" required><?php echo $license['license_key']; ?></textarea>
                </div>
                <div class="form-group">
                    <label for="license_host">License Host</label>
                    <input type="text" name="_edit[license_host]" id="license_host" class="form-control" value="<?php echo $license['license_host']; ?>">
                </div>
                <div class="form-group">
                    <label for="plugin_slug">Plugin*</label>
                    <select name="_edit[plugin_slug]" id="plugin_slug" size="1" class="form-control">
                        <?php
                        echo '<option value=""><i>None</i></option>';
                        foreach ($pluginSlugSelections as $pluginSlugSelection) {
                            echo '<option value="';
                            echo $pluginSlugSelection['slug'];
                            if ($pluginSlugSelection['slug'] === $license['plugin_slug']) {
                                echo '" selected="selected';
                            }
                            echo '">';
                            echo $pluginSlugSelection['plugin_name'];
                            echo '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="valid_until">Valid Until*</label>
                    <input type="text" name="_edit[valid_until]" id="valid_until" class="form-control" required value="<?php echo $license['valid_until']; ?>">
                </div>
                <div class="form-group">
                    <br>
                    <button class="btn btn-success" name="btn_license_edit">Save changes</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <h1 class="mt-5">Info</h1>
            <br>
            <ul>
                <li>You should leave the User and Key unchanged.</li>
                <li>Change the Host if the customer's website URL has changed.</li>
            </ul>
        </div>
    </div>
</div>
