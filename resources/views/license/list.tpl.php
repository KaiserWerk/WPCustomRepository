<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">License List</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <table class="table table-condense table-bordered table-hover table-license">
                <thead>
                <tr>
                    <th>License User</th>
                    <th>License Key</th>
                    <th>License Host</th>
                    <th>Plugin Slug</th>
                    <th style="min-width: 130px;">Valid until</th>

                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($vars['licenses'] as $license) {
                    echo '<tr>';
                    echo '<td>'.$license['license_user'].'</td>';
                    echo '<td><textarea class="form-control-plaintext" onfocus="this.select();" rows="1" cols="50">'.$license['license_key'].'</textarea></td>';
                    echo '<td>'.$license['license_host'].'</td>';
                    echo '<td>'.$license['plugin_slug'].'</td>';
                    echo '<td>'.(new \DateTime($license['valid_until']))->format('Y-m-d').'</td>';
                    $soon = new \DateTime($license['valid_until']);
                    $now = new \DateTime('+12 month');
                    echo '<td>';
                    echo '<a href="/license/'.$license['id'].'/edit">Edit</a>';
                    if ($soon < $now) {
                        echo ' / <a href="/license/'.$license['id'].'/renew">Renew</a> ';
                    }
                    echo ' / <a href="/license/'.$license['id'].'/remove" onclick="return confirm(\'Continue?\');">Remove</a>';
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
