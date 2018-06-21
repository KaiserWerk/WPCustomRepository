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
                <?php if (count($users) > 0): ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?=$user['username'];?></td>
                        <td><?=$user['email'];?></td>
                        <?php if ($user['locked'] === 1): ?>
                            <td><a href="/admin/user/<?=$user['id'];?>/status/locked"><span class="badge badge-warning">Yes</span></a></td>
                        <?php else: ?>
                            <td><a href="/admin/user/<?=$user['id'];?>/status/locked"><span class="badge badge-success">No</span></a></td>
                        <?php endif; ?>
                        
                        <?php if ((int)$user['admin'] === 1): ?>
                            <?php $str_admin = '<a href="/admin/user/'.$user['id'].'/status/admin"><span class="badge badge-primary">Yes</span></a>'; ?>
                        <?php else: ?>
                            <?php $str_admin = '<a href="/admin/user/'.$user['id'].'/status/admin"><span class="badge badge-light">No</span></a>'; ?>
                        <?php endif; ?>
                        <?php if ((int)getenv('SITE_OPERATOR') === $user['id']): ?>
                            <?php $str_admin = '<span class="badge badge-dark">Yes</span>'; ?>
                        <?php endif; ?>
                        <td><?=$str_admin;?></td>
                        <td>
                        <?php if ((int)$user['id'] !== (int)getenv('SITE_OPERATOR')): ?>
                            <a href="/admin/user/<?=$user['id'];?>/edit">Edit</a> /
                            <a href="/admin/user/<?=$user['id'];?>/remove">Remove</a>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


