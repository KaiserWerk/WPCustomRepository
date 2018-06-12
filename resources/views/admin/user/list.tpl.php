<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">User List</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered">
                <thead>
                <tr>
                    <th>Username</th>
                    <th>E-Mail</th>
                    <th>Locked</th>
                    <th>Admin</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($users as $user) {
                    echo '<tr>';
                    echo '<td>'.$user['username'].'</td>';
                    echo '<td>'.$user['email'].'</td>';
                    $label_locked = '<a href="/admin/user/'.$user['id'].'/status/locked"><span class="badge badge-success">No</span></a>';
                    if ($user['locked'] === 1) {
                        $label_locked = '<a href="/admin/user/'.$user['id'].'/status/locked"><span class="badge badge-warning">Yes</span></a>';
                    }
                    echo '<td>'.$label_locked.'</td>';
                    $label_admin = '<a href="/admin/user/'.$user['id'].'/status/admin"><span class="badge badge-light">No</span></a>';
                    if ($user['admin'] === 1) {
                        $label_admin = '<a href="/admin/user/'.$user['id'].'/status/admin"><span class="badge badge-primary">Yes</span></a>';
                    }
                    if ($user['id'] === (int)getenv('SITE_OPERATOR')) {
                        $label_admin = '<span class="badge badge-dark">Yes</span>';
                    }
                    echo '<td>'.$label_admin.'</td>';
                    echo '<td>';
                    if ((int)$user['id'] !== (int)getenv('SITE_OPERATOR')) {
                        echo '<a href="/admin/user/'.$user['id'].'/edit">Edit</a> / ';
                        echo '<a href="/admin/user/'.$user['id'].'/remove">Remove</a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


