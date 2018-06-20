<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Base Theme</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="theme_name">Theme Name</label>
                            <input type="text" name="_theme_base_add[theme_name]" id="plugin_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Theme Slug</label>
                            <input type="text" name="_theme_base_add[slug]" id="slug" class="form-control"required>
                        </div>
                        <div class="form-group">
                            <label for="author">Author</label>
                            <input type="text" name="_theme_base_add[author]" id="author" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="author_homepage">Author Homepage</label>
                            <input type="url" name="_theme_base_add[author_homepage]" id="author_homepage" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="url">URL</label>
                            <input type="url" name="_theme_base_add[url]" id="url" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="section_description">Section Description</label>
                            <script src="/assets/ckeditor/ckeditor.js"></script>
                            <textarea name="_theme_base_add[section_description]" id="section_description" class="form-control"
                                      rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace( 'section_description' );
                            </script>
                        </div>
                        <button type="submit" name="btn_theme_base_add" class="btn btn-primary">Add Base Theme</button>
                    </div>
                </form>
            </div>
        </div>
    </div>