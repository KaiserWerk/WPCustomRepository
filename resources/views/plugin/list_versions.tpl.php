<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5"><?php echo $base_plugin['plugin_name']; ?> - Version List</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php
                if (count($plugin_versions) > 0) {
                    foreach ($plugin_versions as $entry) {
                        echo '<tr style="font-size: 85%;" class="'.($entry['archived'] == 1 ? 'text-black-50' : '').'">';
                        echo '<td class="text-right"><small><b>v' . $entry['version'] . '</b>
                        (<a href="/download/plugin/'.$base_plugin['slug'].'/'.$entry['version'].'"><i class="fa fa-download"></i></a>) </small></td>';
                        echo '<td><small>Requires WP: ' . $entry['requires'] . '</small></td>';
                        echo '<td><small>Tested: ' . $entry['tested'] . '</small></td>';
                        echo '<td><small>Requires PHP: ' . $entry['requires_php'] . '</small></td>';
                        echo '<td><small>Downloaded: '.$entry['downloaded'].'</small></td>';
                        echo '<td><small>Installations: '.$entry['active_installations'].'</small></td>';
                        #echo '<td><small>Archived: <a href="/plugin/version/'.$entry['id'].'/toggle-archived">' . ($entry['archived'] == 0 ? 'No' : 'Yes') . '</a></small></td>';
                        echo '<td><small>Added: ' . (new \DateTime($entry['added_at']))->format('Y-m-d') . '</small></td>';
                        echo '<td><small>';
                        echo '<a href="/plugin/version/' . $entry['id'] . '/show">Show</a>';
                        echo ' / <a href="/plugin/version/' . $entry['id'] . '/edit">Edit</a>';
                        echo ' / <a href="/plugin/version/' . $entry['id'] . '/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                        echo '</small></td>';
                        echo '</tr>';
                    }
                } else {
                    echo '<tr><td class="text-center text-muted"><small>No versions found for plugin <b>'.$base_plugin['plugin_name'].'</b>!</small></td> </tr>';
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </div>