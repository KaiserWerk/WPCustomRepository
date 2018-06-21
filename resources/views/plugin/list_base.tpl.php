<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Base Plugin list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php foreach ($base_plugins as $base_plugin): ?>
                    <tr>
                        <td width="34%"><?=$base_plugin['plugin_name'];?> &nbsp; <small><i><?=$base_plugin['slug'];?></i></small></td>
                        <td width="33%">Updated: <?=(new \DateTime($base_plugin['last_updated']))->format('Y-m-d');?></td>
                        <td width="33%">
                        <a href="/plugin/base/<?=$base_plugin['id'];?>/edit">Edit</a>
                         / <a href="/plugin/version/<?=$base_plugin['id'];?>/list">Show Versions</a>
                         / <a href="/plugin/base/<?=$base_plugin['id'];?>/remove" onclick="return confirm('Continue?');">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            
        </div>
    </div>
</div>