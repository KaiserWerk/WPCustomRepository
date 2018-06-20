<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Theme <strong><?php echo $base_theme['theme_name']; ?></strong> Version list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php
                foreach ($theme_versions as $entry) {
                    echo '<tr>';
                    echo '<td class="text-right"><small><b>v' . $entry['version'] . '</b>
                    (<a href="/download/plugin/'.$base_theme['slug'].'/'.$entry['version'].'"><i class="fa fa-download"></i></a>) </small></td>';
                    echo '<td><small>Requires WP: ' . $entry['requires'] . '</small></td>';
                    echo '<td><small>Tested: ' . $entry['tested'] . '</small></td>';
                    echo '<td><small>Requires PHP: ' . $entry['requires_php'] . '</small></td>';
                    echo '<td><small>Downloaded: '.$entry['downloaded'].'</small></td>';
                    echo '<td><small>Installations: '.$entry['active_installations'].'</small></td>';
                    echo '<td><small>Added: ' . (new \DateTime($entry['added_at']))->format('Y-m-d') . '</small></td>';
                    echo '<td><small>';
                    echo '<a href="/theme/version/' . $entry['id'] . '/show">Show</a>';
                    echo ' / <a href="/theme/version/' . $entry['id'] . '/edit">Edit</a>';
                    echo ' / <a href="/theme/version/' . $entry['id'] . '/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                    echo '</small></td>';
                    echo '</tr>';
                    
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>