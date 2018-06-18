<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Show Plugin Details</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <h4><?php echo $base_plugin['plugin_name']; ?></h4>
            <table class="table table-borderless table-condensed">
                <tbody>
                <tr>
                    <td>Slug</td>
                    <td><?php echo $base_plugin['slug']; ?></td>
                </tr>
                
                <tr>
                    <td>Section Description</td>
                    <td><?php echo $base_plugin['section_description']; ?></td>
                </tr>
                <tr>
                    <td rowspan="2">
                        <a href="/plugin/base/<?php echo $base_plugin['id']; ?>/edit" class="btn btn-primary">Edit this plugin</a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>