<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-5">Edit License</h1>
            <?php Helper::getMessage(); ?>
            <form method="post">
                <?php
                AuthHelper::generateCSRFInput();
                AuthHelper::generateHoneypotInput();
                ?>
                <div class="form-group">
                    <label for="license_user">License User*</label>
                    <input type="text" name="_edit[license_user]" id="license_user" class="form-control" required value="<?=$license['license_user'];?>">
                </div>
                <div class="form-group">
                    <label for="license_key">License Key*</label>
                    <textarea name="_edit[license_key]" id="license_key" class="form-control" maxlength="200" rows="4" required><?=$license['license_key'];?></textarea>
                </div>
                <div class="form-group">
                    <label for="license_host">License Host</label>
                    <input type="text" name="_edit[license_host]" id="license_host" class="form-control" value="<?=$license['license_host'];?>">
                </div>
                <div class="form-group">
                    <label for="plugin_slug">Plugin*</label>
                    <select name="_edit[plugin_slug]" id="plugin_slug" size="1" class="form-control">
                        <option value=""><i>None</i></option>
                        <?php foreach ($base_plugins as $base_plugin): ?>
                            <option value="<?=$base_plugin['slug'];?>" <?php if ($base_plugin['slug'] === $license['plugin_slug']): ?>
                                 selected="selected"<?php endif; ?>">
                            <?=$base_plugin['plugin_name'];?>
                            </option>
                        <?php endforeach; ?>
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
