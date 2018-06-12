<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Show Plugin Details</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <h4><?php echo $plugin['plugin_name']; ?> (<a href="/download/plugin/<?php echo $plugin['slug']; ?>"><i class="fa fa-download"></i></a>)</h4>
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td>Slug</td>
                    <td><?php echo $plugin['slug']; ?></td>
                </tr>
                <tr>
                    <td>Version</td>
                    <td><?php echo $plugin['version']; ?></td>
                </tr>
                <tr>
                    <td>Requires</td>
                    <td><?php echo $plugin['requires']; ?></td>
                </tr>
                <tr>
                    <td>Tested</td>
                    <td><?php echo $plugin['tested']; ?></td>
                </tr>
                <tr>
                    <td>Homepage</td>
                    <td><a href="<?php echo $plugin['homepage']; ?>" target="_blank"><?php echo $plugin['homepage']; ?></a></td>
                </tr>
                <tr>
                    <td>Section Description</td>
                    <td><?php echo $plugin['section_description']; ?></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <a href="/plugin/<?php echo $plugin['id']; ?>/edit" class="btn btn-primary">Edit this plugin</a>
                    </td>
                </tr>
                </tbody>
            </table>
            
        </div>
    </div>