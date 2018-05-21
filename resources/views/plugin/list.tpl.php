<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Plugin list <a class="btn btn-success btn-xs" href="/plugin/add">Add plugin</a></h1>
            <br>
            <table class="table">
                <thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Plugin Slug</th>
                    <th>Version</th>
                    <th>Download</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ( $rows as $row ) {
                    echo '<tr>';
                    echo '<td>' . $row['plugin_name'] . '</td>';
                    echo '<td>' . $row['slug'] . '</td>';
                    echo '<td>' . $row['version'] . '</td>';
                    echo '<td><a href="/download/plugin/' . $row['slug'] . '">Link</a></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </div>