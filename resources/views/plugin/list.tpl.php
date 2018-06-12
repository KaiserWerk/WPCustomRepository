<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Plugin list</h1>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <!--<thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Plugin Slug</th>
                    <th>Version</th>
                    <th>Action</th>
                </tr>
                </thead>-->
                <tbody>
                <?php

                foreach ($base_plugins as $base_plugin) {
                    echo '<tr>';
                    echo '<td colspan="5">' . $base_plugin['plugin_name'] . ' &nbsp; <small><i>' . $base_plugin['slug'] . '</i></small></td>';
                    echo '<td>Updated: ' . (new \DateTime($base_plugin['last_updated']))->format('Y-m-d') . '</td>';
                    echo '<td>';
                    echo '<a href="/plugin/base/' . $base_plugin['id'] . '/edit">Edit</a>';
                    echo '</td>';
                    echo '</tr>';
                    if (count($base_plugin['entries']) > 0) {
                        foreach ($base_plugin['entries'] as $entry) {
                            echo '<tr class="'.($entry['archived'] == 1 ? 'text-black-50' : '').'">';
                            echo '<td class="text-right"><small><b>v' . $entry['version'] . '</b>
                            (<a href="/download/plugin/'.$base_plugin['slug'].'/'.$entry['version'].'"><i class="fa fa-download"></i></a>) </small></td>';
                            echo '<td><small>Requires WP: ' . $entry['requires'] . '</small></td>';
                            echo '<td><small>Tested: ' . $entry['tested'] . '</small></td>';
                            echo '<td><small>Requires PHP: ' . $entry['requires_php'] . '</small></td>';
                            echo '<td><small>Archived: <a href="/plugin/version/'.$entry['id'].'/toggle-archived">' . ($entry['archived'] == 0 ? 'No' : 'Yes') . '</a></small></td>';
                            echo '<td><small>Added: ' . (new \DateTime($entry['created_at']))->format('Y-m-d') . '</small></td>';
                            echo '<td><small>';
                            echo '<a href="/plugin/version/' . $entry['id'] . '/show">Show</a>';
                            echo ' / <a href="/plugin/version/' . $entry['id'] . '/edit">Edit</a>';
                            echo ' / <a href="/plugin/version/' . $entry['id'] . '/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                            echo '</small></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="6" class="text-center text-muted"><small>No versions found for plugin <b>'.$base_plugin['plugin_name'].'</b>!</small></td> </tr>';
                    }
                    
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </div>