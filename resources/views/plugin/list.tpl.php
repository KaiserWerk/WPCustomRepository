<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Plugin list</h1>
            <br>
            <table class="table table-condense table-bordered table-hover">
                <thead>
                <tr>
                    <th>Plugin Name</th>
                    <th>Plugin Slug</th>
                    <th>Version</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ( $rows as $row ) {
                    echo '<tr>';
                    echo '<td>' . $row['plugin_name'] . '</td>';
                    echo '<td>' . $row['slug'] . '</td>';
                    echo '<td>' . $row['version'] . '</td>';
                    echo '<td><a href="/plugin/' . $row['id'] . '/edit">Edit</a> / <a href="/plugin/' . $row['id'] . '/archive">Archive</a></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
            
        </div>
    </div>