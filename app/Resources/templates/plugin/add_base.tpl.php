<script src="/assets/ckeditor/ckeditor.js"></script>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Add Base Plugin</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <form method="post" class="row" enctype="multipart/form-data" style="width: 100%;">
                    <?php AuthHelper::generateCSRFInput(); ?>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group">
                            <label for="plugin_name">Plugin Name*</label>
                            <input type="text" name="_plugin_base_add[plugin_name]" id="plugin_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="slug">Plugin Slug*</label>
                            <input type="text" name="_plugin_base_add[slug]" id="slug" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="homepage">Homepage*</label>
                            <input type="url" name="_plugin_base_add[homepage]" id="homepage" class="form-control" placeholder="http://">
                        </div>
                        <div class="form-group">
                            <label for="_plugin_base_add_banner_low">Plugin Banner (low)*</label>
                            <input type="file" name="_plugin_base_add_banner_low" id="_plugin_base_add_banner_low" class="form-control-file">
                        </div>
                        <div class="form-group">
                            <label for="_plugin_base_add_banner_high">Plugin Banner (high)</label>
                            <input type="file" name="_plugin_base_add_banner_high" id="_plugin_base_add_banner_high" class="form-control-file">
                        </div>

                        <br>
                        <button type="submit" name="btn_plugin_base_add" class="btn btn-primary btn-lg">Add Plugin</button>
                        
                    </div>
                    
                    <div class="col-md-6 col-sm-12">

                        <div class="accordion" id="accordionExample">

                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <label for="section_description">Description*</label>
                                        </button>
                                    </h5>
                                </div>

                                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_description]" id="section_description" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_description');</script>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="card">
                                <div class="card-header" id="heading4">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse4" aria-expanded="false" aria-controls="collapse4">
                                            <label for="section_installation">Installation</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse4" class="collapse" aria-labelledby="heading4" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_installation]" id="section_installation" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_installation');</script>
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <label for="section_screenshots">Screenshots</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_screenshots]" id="section_screenshots" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_screenshots');</script>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            <label for="section_other_notes">Other Notes</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_other_notes]" id="section_other_notes" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_other_notes');</script>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading5">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse5" aria-expanded="false" aria-controls="collapse5">
                                            <label for="section_faq">FAQ</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse5" class="collapse" aria-labelledby="heading5" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_faq]" id="section_faq" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_faq');</script>
                                    </div>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header" id="heading6">
                                    <h5 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapse6" aria-expanded="false" aria-controls="collapse6">
                                            <label for="section_changelog">Changelog</label>
                                        </button>
                                    </h5>
                                </div>
                                <div id="collapse6" class="collapse" aria-labelledby="heading6" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <textarea name="_plugin_base_add[section_changelog]" id="section_changelog" class="form-control" rows="8" required></textarea>
                                        <script>CKEDITOR.replace('section_changelog');</script>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>