<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Theme <strong><?=$base_theme['theme_name'];?></strong> Version list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php if (count($theme_versions) > 0): ?>
                    <?php foreach ($theme_versions as $entry): ?>
                    <tr>
                        <td class="text-right"><small><b>v<?=$entry['version'];?></b>
                        (<a href="/download/theme/<?=$base_theme['slug'];?>/<?=$entry['version'];?>"><i class="fa fa-download"></i></a>) </small></td>
                        <td><small>Requires WP: <?=$entry['requires'];?></small></td>
                        <td><small>Tested: <?=$entry['tested'];?></small></td>
                        <td><small>Requires PHP: <?=$entry['requires_php'];?></small></td>
                        <td><small>Downloaded: <?=$entry['downloaded'];?></small></td>
                        <td><small>Installations: <?=$entry['active_installations'];?></small></td>
                        <td><small>Added: <?=(new \DateTime($entry['added_at']))->format('Y-m-d');?></small></td>
                        <td><small>
                        <a href="/theme/version/<?=$entry['id'];?>/show">Show</a>
                         / <a href="/theme/version/<?=$entry['id'];?>/edit">Edit</a>
                         / <a href="/theme/version/<?=$entry['id'];?>/remove" onclick="return confirm('Continue?');">Remove</a>
                        </small></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="8" class="text-center">No theme versions found!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>