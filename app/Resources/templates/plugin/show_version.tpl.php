<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Show Plugin Details</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <h4><?=$base_plugin['plugin_name'];?> (<a href="/download/plugin/<?=$base_plugin['slug'];?>/<?=$plugin_version['version'];?>"><i class="fa fa-download"></i></a>)</h4>
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td>Slug</td>
                    <td><?=$base_plugin['slug'];?></td>
                </tr>
                <tr>
                    <td>Version</td>
                    <td><strong><?=$plugin_version['version'];?></strong></td>
                </tr>
                <tr>
                    <td>Requires</td>
                    <td><?=$plugin_version['requires'];?></td>
                </tr>
                <tr>
                    <td>Requires PHP</td>
                    <td><?=$plugin_version['requires_php'];?></td>
                </tr>
                <tr>
                    <td>Tested</td>
                    <td><?=$plugin_version['tested'];?></td>
                </tr>
                <tr>
                    <td>Homepage</td>
                    <td><a href="<?=$base_plugin['homepage'];?>" target="_blank"><?=$base_plugin['homepage'];?></a></td>
                </tr>
                <tr>
                    <td>Section Description</td>
                    <td><?=$base_plugin['section_description'];?></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <a href="/plugin/version/<?=$plugin_version['id'];?>/edit" class="btn btn-primary">Edit this plugin version</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>