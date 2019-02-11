<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Theme Version</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Base Plugin Name</label>
                            <select name="_theme_version_add[theme_entry_id]" id="theme_entry_id" class="form-control" size="1" required>
                                <?php foreach ($base_themes as $base_theme): ?>
                                    <option value="<?=$base_theme['id'];?>"><?=$base_theme['theme_name'];?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="version">Version <small>(<a href="https://semver.org/" target="_blank">see SemVer</a>)</small></label>
                            <input type="text" name="_theme_version_add[version]" id="version" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="requires">Required WP Version</label>
                            <input type="text" name="_theme_version_add[requires]" id="requires" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested with WP Version</label>
                            <input type="text" name="_theme_version_add[tested]" id="tested" class="form-control" value="<?php Helper::getCurrentWPVersion(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="requires_php">Required PHP Version</label>
                            <input type="text" name="_theme_version_add[requires_php]" id="requires_php" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_file">Theme File</label>
                            <input type="file" name="_theme_version_add_theme_file" id="theme_file" class="form-control-file" required>
                        </div>
                        <button type="submit" name="btn_theme_version_add" class="btn btn-primary">Add Theme Version</button>
                    </div>
                </form>
            </div>
        </div>
    </div>