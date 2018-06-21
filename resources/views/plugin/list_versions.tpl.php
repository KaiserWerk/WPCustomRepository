<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5"><?php echo $base_plugin['plugin_name']; ?> - Version List</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php if (count($plugin_versions) > 0): ?>
                    <?php foreach ($plugin_versions as $entry): ?>
                    <tr style="font-size: 85%;" class="<?=($entry['archived'] == 1 ? 'text-black-50' : '');?>">
                        <td class="text-right"><small><b>v<?=$entry['version'];?></b>
                        (<a href="/download/plugin/<?=$base_plugin['slug'];?>/<?=$entry['version'];?>"><i class="fa fa-download"></i></a>) </small></td>
                        <td><small>Requires WP: <?=$entry['requires'];?></small></td>
                        <td><small>Tested: <?=$entry['tested'];?></small></td>
                        <td><small>Requires PHP: <?=$entry['requires_php'];?></small></td>
                        <td><small>Downloaded: <?=$entry['downloaded'];?></small></td>
                        <td><small>Installations: <?=$entry['active_installations'];?></small></td>
                        <td><small>Added: <?=(new \DateTime($entry['added_at']))->format('Y-m-d');?></small></td>
                        <td><small>
                        <a href="/plugin/version/<?=$entry['id'];?>/show">Show</a>
                         / <a href="/plugin/version/<?=$entry['id'];?>/edit">Edit</a>
                         / <a href="/plugin/version/<?=$entry['id'];?>/remove" onclick="return confirm('Continue?');">Remove</a>
                        </small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td class="text-center text-muted"><small>No versions found for plugin <b><?=$base_plugin['plugin_name'];?></b>!</small></td> </tr>
                <?php endif; ?>
                </tbody>
            </table>
            
        </div>
    </div>