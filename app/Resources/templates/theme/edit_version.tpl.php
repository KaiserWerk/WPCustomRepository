<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Edit Theme Version</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            Theme Name<br>
                            <?=$base_theme['theme_name'];?>
                        </div>
                        <div class="form-group">
                            Theme Slug<br>
                            <?=$base_theme['slug'];?>
                        </div>
                        <div class="form-group">
                            <label for="version">Version</label>
                            <input type="text" name="_theme_version_edit[version]" id="version" class="form-control" value="<?=$theme_version['version'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="requires_php">Requires PHP</label>
                            <input type="text" name="_theme_version_edit[requires_php]" id="requires_php" class="form-control" value="<?=$theme_version['requires_php'];?>">
                        </div>
                        <div class="form-group">
                            <label for="requires">Requires WP</label>
                            <input type="text" name="_theme_version_edit[requires]" id="requires" class="form-control" value="<?=$theme_version['requires'];?>">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested with WP</label>
                            <input type="text" name="_theme_version_edit[tested]" id="tested" class="form-control" value="<?=$theme_version['tested'];?>">
                        </div>
    
                        <button type="submit" name="btn_theme_version_edit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>