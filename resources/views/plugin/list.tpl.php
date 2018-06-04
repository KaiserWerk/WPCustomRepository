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
                    echo '<td colspan="4">' . $base_plugin['plugin_name'] . '</td>';
                    echo '<td><i>' . $base_plugin['slug'] . '</i></td>';
                    echo '<td>Updated: ' . (new \DateTime($base_plugin['last_updated']))->format('d.m.Y') . '</td>';
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
                            echo '<td><small>Archived: ' . ($entry['archived'] == 0 ? 'No' : 'Yes') . '</small></td>';
                            echo '<td><small>Added: ' . (new \DateTime($base_plugin['last_updated']))->format('d.m.Y') . '</small></td>';
                            echo '<td><small>';
                            echo '<a href="/plugin/version/' . $entry['id'] . '/show">Show</a>';
                            echo ' / <a href="/plugin/version/' . $entry['id'] . '/edit">Edit</a>';
                            echo '</small></td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="7" class="text-center text-muted"><small>No versions found for plugin <b>'.$base_plugin['plugin_name'].'</b>!</small></td> </tr>';
                    }
                    
                }
                
                /*foreach ( $rows as $row ) {
                    echo '<tr>';
                    echo '<td>' . $row['plugin_name'] . '</td>';
                    echo '<td>' . $row['slug'] . '</td>';
                    echo '<td>' . $row['version'] . '</td>';
                    echo '<td>';
                    echo '<a href="/plugin/' . $row['id'] . '/show">Show</a> / ';
                    echo '<a href="/plugin/' . $row['id'] . '/edit">Edit</a> / ';
                    echo '<a href="/plugin/' . $row['id'] . '/archive">Archive</a>';
                    echo '</td>';
                    echo '</tr>';
                }*/
                ?>
                </tbody>
            </table>
            
        </div>
    </div>