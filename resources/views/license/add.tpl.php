<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-5">Add License</h1>
            <?php Helper::getMessage(); ?>
            <form method="post">
                <?php
                AuthHelper::generateCSRFInput();
                AuthHelper::generateHoneypotInput();
                ?>
                <div class="form-group">
                    <label for="license_user">License User*</label>
                    <input type="text" name="_add[license_user]" id="license_user" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="license_key">License Key*</label>
                    <textarea name="_add[license_key]" id="license_key" class="form-control" maxlength="200" rows="4" required><?=$key;?></textarea>
                </div>
                <div class="form-group">
                    <label for="license_host">License Host</label>
                    <input type="text" name="_add[license_host]" id="license_host" class="form-control">
                </div>
                <div class="form-group">
                    <label for="plugin_slug">Plugin*</label>
                    <select name="_add[plugin_slug]" id="plugin_slug" size="1" class="form-control">
                        <option value=""><i>None</i></option>
                        <?php foreach ($pluginSlugSelections as $pluginSlugSelection): ?>
                            <option value="<?=$pluginSlugSelection['slug'];?>"><?=$pluginSlugSelection['plugin_name'];?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="valid_until">Valid Until*</label>
                    <input type="text" name="_add[valid_until]" id="valid_until" class="form-control" required value="<?=date('Y')+1 . '-' . date('m-d');?>">
                </div>
                <div class="form-group">
                    <br>
                    <button class="btn btn-success" name="license_button">Add New License</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <h1 class="mt-5">Info</h1>
            <br>
            <ul>
                <li>License keys can be up to 200 characters long, but do not have to be. They can be shorter.</li>
                <li>By entering a host, you can restrict the license to this host. This is optional.</li>
                <li>You can choose a plugin for which the license will only be valid. If you choose none, the license will be valid for all plugins.</li>
                <li>You can set the validity date as far into the future as you want, but usually, you set it to 1 year (default) and renew the license regularly.</li>
            </ul>
        </div>
    </div>
</div>
