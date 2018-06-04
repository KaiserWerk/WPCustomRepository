<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Plugin</h1>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Plugin Name</label>
                            <input type="text" name="_plugin_add[plugin_name]" id="plugin_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Plugin Slug</label>
                            <input type="text" name="_plugin_add[slug]" id="slug" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="version">Version <small>(<a href="https://semver.org/" target="_blank">see SemVer</a>)</small></label>
                            <input type="text" name="_plugin_add[version]" id="version" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="requires">Requires</label>
                            <input type="text" name="_plugin_add[requires]" id="requires" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested</label>
                            <input type="text" name="_plugin_add[tested]" id="tested" class="form-control" value="<?php Helper::getCurrentWPVersion(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="homepage">Homepage</label>
                            <input type="text" name="_plugin_add[homepage]" id="homepage" class="form-control" placeholder="http://" value="<?php echo Helper::getHost(); ?>/info/plugin/">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="section_description">Section Description</label>
                            <script src="/assets/ckeditor/ckeditor.js"></script>
                            <textarea name="_plugin_add[section_description]" id="section_description" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace( 'section_description' );
                            </script>
                        </div>
                        <div class="form-group">
                            <label for="plugin_file">Plugin File</label>
                            <input type="file" name="_plugin_add_plugin_file" id="plugin_file" class="form-control-file" required>
                        </div>
                        <button type="submit" name="btn_plugin_add" class="btn btn-primary">Add Plugin</button>
                    </div>
                </form>
            </div>
        </div>
    </div>