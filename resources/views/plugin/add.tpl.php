<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Plugin</h1>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <form method="post" enctype="multipart/form-data">
                        <?php AuthHelper::generateCSRFInput(); ?>
                        Plugin Name:
                        <input type="text" name="_plugin_add[plugin_name]" class="form-control" required><br>
                        Plugin Slug:
                        <input type="text" name="_plugin_add[slug]" class="form-control" required><br>
                        Version:
                        <input type="text" name="_plugin_add[version]" class="form-control" required><br>
                        Requires:
                        <input type="text" name="_plugin_add[requires]" class="form-control"><br>
                        Tested:
                        <input type="text" name="_plugin_add[tested]" class="form-control"><br>
                        Homepage:
                        <input type="text" name="_plugin_add[homepage]" class="form-control"><br>
                        Section Description:
                        <textarea name="_plugin_add[section_description]" class="form-control" rows="6" required></textarea><br>
                        File:
                        <input type="file" name="_plugin_add_plugin_file" class="form-control" required><br>
                        <input type="submit" value="Add Plugin" class="btn btn-primary">
                    </form>
                </div>
                <div class="col-md-6">
                
                </div>
            </div>
        </div>
    </div>