<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Plugin Version</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Base Plugin Name</label>
                            <select name="_plugin_add[plugin_name]" id="plugin_name" class="form-control" size="1" required>
                            <?php
                            foreach ($vars['base_plugins'] as $base_plugin) {
                                echo '<option value="';
                                echo $base_plugin['id'];
                                echo '">';
                                echo $base_plugin['plugin_name'];
                                echo '</option>';
                            }
                            ?>
                            </select>
                        </div>
                       
                        <div class="form-group">
                            <label for="version">Version <small>(<a href="https://semver.org/" target="_blank">see SemVer</a>)</small></label>
                            <input type="text" name="_plugin_add[version]" id="version" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="requires">Required WP Version</label>
                            <input type="text" name="_plugin_add[requires]" id="requires" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="tested">Tested with WP Version</label>
                            <input type="text" name="_plugin_add[tested]" id="tested" class="form-control" value="<?php Helper::getCurrentWPVersion(); ?>">
                        </div>
                        <div class="form-group">
                            <label for="requires_php">Required PHP Version</label>
                            <input type="text" name="_plugin_add[requires_php]" id="requires_php" class="form-control">
                        </div>
                        
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="homepage">Homepage</label>
                            <input type="text" name="_plugin_add[homepage]" id="homepage" class="form-control" placeholder="http://" value="<?php echo Helper::getHost(); ?>/plugin/">
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