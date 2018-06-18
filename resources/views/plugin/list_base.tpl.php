<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Base Plugin list</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <tbody>
                <?php

                foreach ($base_plugins as $base_plugin) {
                    echo '<tr>';
                    echo '<td colspan="7">' . $base_plugin['plugin_name'] . ' &nbsp; <small><i>' . $base_plugin['slug'] . '</i></small></td>';
                    echo '<td>Updated: ' . (new \DateTime($base_plugin['last_updated']))->format('Y-m-d') . '</td>';
                    echo '<td>';
                    echo '<a href="/plugin/base/' . $base_plugin['id'] . '/edit">Edit</a>';
                    echo ' / <a href="/plugin/version/' . $base_plugin['id'] . '/list">Show Versions</a>';
                    echo ' / <a href="/plugin/base/' . $base_plugin['id'] . '/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                    echo '</td>';
                    echo '</tr>';
                    
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </div>
</div>