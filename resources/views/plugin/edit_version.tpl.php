<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Edit Plugin Version</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label class="control-label" for="email">Plugin Name:</label>
                            <p class="form-control-static"><strong><?=$base_plugin['plugin_name'];?></strong></p>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="email">Plugin Slug:</label>
                            <p class="form-control-static"><strong><?=$base_plugin['slug'];?></strong></p>
                        </div>
                        <div class="form-group">
                            <label for="version">Version <small>(<a href="https://semver.org/" target="_blank">see SemVer</a>)</small></label>
                            <input type="text" name="_plugin_version_edit[version]" id="version" class="form-control" value="<?=$plugin_version['version'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="requires">Requires PHP</label>
                            <input type="text" name="_plugin_version_edit[requires_php]" id="requires" class="form-control" value="<?=$plugin_version['requires_php'];?>">
                        </div>
                        <div class="form-group">
                            <label for="requires">Requires</label>
                            <input type="text" name="_plugin_version_edit[requires]" id="requires" class="form-control" value="<?=$plugin_version['requires'];?>">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested</label>
                            <input type="text" name="_plugin_version_edit[tested]" id="tested" class="form-control" value="<?=$plugin_version['tested'];?>">
                        </div>
                        <button type="submit" name="btn_plugin_version_edit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>