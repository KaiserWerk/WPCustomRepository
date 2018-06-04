<script src="/assets/ckeditor/ckeditor.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Base Plugin</h1>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Plugin Name*</label>
                            <input type="text" name="_plugin_add[plugin_name]" id="plugin_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Plugin Slug*</label>
                            <input type="text" name="_plugin_add[slug]" id="slug" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="homepage">Homepage*</label>
                            <input type="url" name="_plugin_add[homepage]" id="homepage" class="form-control" placeholder="http://">
                        </div>
                        <div class="form-group">
                            <label for="_plugin_add_banner_low">Plugin Banner (low)*</label>
                            <input type="file" name="_plugin_add_banner_low" id="_plugin_add_banner_low" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <label for="_plugin_add_banner_high">Plugin Banner (high)</label>
                            <input type="file" name="_plugin_add_banner_high" id="_plugin_add_banner_high" class="form-control-file">
                        </div>

                        <div class="form-group">
                            <label for="section_installation">Section - Installation</label><br>
                            <textarea name="_plugin_add[section_installation]" id="section_installation" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_installation');
                            </script>
                        </div>

                        <div class="form-group">
                            <label for="section_screenshots">Section - Screenshots</label><br>
                            <textarea name="_plugin_add[section_screenshots]" id="section_screenshots" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_screenshots');
                            </script>
                        </div>

                        <div class="form-group">
                            <label for="section_other_notes">Section - Other Notes</label><br>
                            <textarea name="_plugin_add[section_other_notes]" id="section_other_notes" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_other_notes');
                            </script>
                        </div>
                        
                    </div>
                    
                    <div class="col-md-6 col-sm-12">
                        <button type="submit" name="btn_plugin_add" class="btn btn-primary btn-lg">Add Plugin</button>
                        <br><br>

                        <div class="form-group">
                            <label for="section_description">Section - Description*</label><br>
                            <textarea name="_plugin_add[section_description]" id="section_description" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_description');
                            </script>
                        </div>

                        <div class="form-group">
                            <label for="section_faq">Section - FAQ</label><br>
                            <textarea name="_plugin_add[section_faq]" id="section_faq" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_faq');
                            </script>
                        </div>

                        <div class="form-group">
                            <label for="section_changelog">Section - Changelog</label><br>
                            <textarea name="_plugin_add[section_changelog]" id="section_changelog" class="form-control" rows="8" required></textarea>
                            <script>
                                CKEDITOR.replace('section_changelog');
                            </script>
                        </div>
                            
                        
                    </div>
                </form>
            </div>
        </div>
    </div>