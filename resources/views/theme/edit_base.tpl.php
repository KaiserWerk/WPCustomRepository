<?php $base_theme = $vars['base_theme']; ?>
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
                            <label for="theme_name">Plugin Name</label>
                            <input type="text" name="_theme_base_edit[theme_name]" id="plugin_name" class="form-control" value="<?php echo $base_theme['theme_name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Theme Slug</label>
                            <input type="text" name="_theme_base_edit[slug]" id="slug" class="form-control" value="<?php echo $base_theme['slug']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" name="_theme_base_edit[author]" id="author" class="form-control" value="<?php echo $base_theme['author']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="author_homepage">Author Homepage</label>
                            <input type="url" name="_theme_base_edit[author_homepage]" id="author_homepage" class="form-control" value="<?php echo $base_theme['author_homepage']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" name="_theme_base_edit[url]" id="url" class="form-control" value="<?php echo $base_theme['url']; ?>">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="section_description">Section Description</label>
                            <script src="/assets/ckeditor/ckeditor.js"></script>
                            <textarea name="_theme_base_edit[section_description]" id="section_description" class="form-control"
                                      rows="8" required><?php echo $base_theme['section_description']; ?></textarea>
                            <script>
                                CKEDITOR.replace( 'section_description' );
                            </script>
                        </div>
                        <button type="submit" name="btn_theme_base_edit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>