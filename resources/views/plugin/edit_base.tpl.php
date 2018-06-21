<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Edit Plugin</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Plugin Name</label>
                            <input type="text" name="_plugin_edit[plugin_name]" id="plugin_name" class="form-control" value="<?=$plugin['plugin_name'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Plugin Slug</label>
                            <input type="text" name="_plugin_edit[slug]" id="slug" class="form-control" value="<?=$plugin['slug'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="version">Version <small>(<a href="https://semver.org/" target="_blank">see SemVer</a>)</small></label>
                            <input type="text" name="_plugin_edit[version]" id="version" class="form-control" value="<?=$plugin['version'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="requires">Requires</label>
                            <input type="text" name="_plugin_edit[requires]" id="requires" class="form-control" value="<?=$plugin['requires'];?>">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested</label>
                            <input type="text" name="_plugin_edit[tested]" id="tested" class="form-control" value="<?=$plugin['tested'];?>">
                        </div>
                        <div class="form-group">
                            <label for="homepage">Homepage</label>
                            <input type="text" name="_plugin_edit[homepage]" id="homepage" class="form-control" value="<?=$plugin['homepage'];?>" placeholder="http://">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="section_description">Section Description</label>
                            <script src="/assets/ckeditor/ckeditor.js"></script>
                            <textarea name="_plugin_edit[section_description]" id="section_description" class="form-control" rows="8" required><?=$plugin['section_description'];?></textarea>
                            <script>
                                CKEDITOR.replace( 'section_description' );
                            </script>
                        </div>
                        <button type="submit" name="btn_plugin_edit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>