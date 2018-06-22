<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Base Theme list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php if (count($base_themes) > 0): ?>
                    <?php foreach ($base_themes as $base_theme): ?>
                    <tr>
                        <td width="34%"><?=$base_theme['theme_name'];?> &nbsp; <small><i><?=$base_theme['slug'];?></i></small></td>
                        <td width="33%">Updated: <?=(new \DateTime($base_theme['last_updated']))->format('Y-m-d');?></td>
                        <td width="33%">
                        <a href="/theme/base/<?=$base_theme['id'];?>/show">Show</a>
                         / <a href="/theme/base/<?=$base_theme['id'];?>/edit">Edit</a>
                         / <a href="/theme/version/<?=$base_theme['id'];?>/list">Show Versions</a>
                         / <a href="/theme/base/<?=$base_theme['id'];?>/remove" onclick="return confirm('Continue?');">Remove</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center">No base themes found!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>