<div class="container">
    <div class="row">
        <div class="col-md-6">
            <h1 class="mt-5">Show Theme Details</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <h4><?=$base_theme['theme_name'];?> (<a href="/download/theme/<?=$base_theme['slug'];?>/<?=$theme_version['version'];?>"><i class="fa fa-download"></i></a>)</h4>
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td>Slug</td>
                    <td><?=$base_theme['slug'];?></td>
                </tr>
                <tr>
                    <td>Version</td>
                    <td><strong><?=$theme_version['version'];?></strong></td>
                </tr>
                <tr>
                    <td>Requires</td>
                    <td><?=$theme_version['requires'];?></td>
                </tr>
                <tr>
                    <td>Requires PHP</td>
                    <td><?=$theme_version['requires_php'];?></td>
                </tr>
                <tr>
                    <td>Tested</td>
                    <td><?=$theme_version['tested'];?></td>
                </tr>
                <tr>
                    <td>Downloads</td>
                    <td><?=$theme_version['downloaded'];?></td>
                </tr>
                <tr>
                    <td>Active installations</td>
                    <td><?=$theme_version['active_installations'];?></td>
                </tr>
                <tr>
                    <td>Homepage</td>
                    <td><a href="<?=$base_theme['homepage'];?>" target="_blank"><?=$base_theme['homepage'];?></a></td>
                </tr>
                <tr>
                    <td>Section Description</td>
                    <td><?=$base_theme['section_description'];?></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <a href="/theme/version/<?=$theme_version['id'];?>/edit" class="btn btn-primary">Edit this theme version</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>