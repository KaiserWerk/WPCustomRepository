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
                    <th>Plugin or Theme</th>
                    <th style="min-width: 130px;">Valid until</th>

                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($licenses as $license): ?>
                    <?php
                    $base_plugin = Helper::basePluginByID($license['plugin_entry_id'])['plugin_name'] ?? '';
                    $base_theme = Helper::baseThemeByID($license['theme_entry_id'])['theme_name'] ?? '';
                    ?>
                    <tr>
                        <td><?=$license['license_user'];?></td>
                        <td><textarea class="form-control-plaintext" onfocus="this.select();" rows="1" cols="50"><?=$license['license_key'];?></textarea></td>
                        <td><?=$license['license_host'];?></td>
                        <td><?=$base_plugin;?><?=$base_theme;?></td>
                        <td><?=(new \DateTime($license['valid_until']))->format('Y-m-d');?></td>
                        <?php
                        $soon = new \DateTime($license['valid_until']);
                        $now = new \DateTime('+12 month');
                        ?>
                        <td>
                        <a href="/license/<?=$license['id'];?>/edit">Edit</a>
                        <?php if ($soon < $now): ?>
                             / <a href="/license/<?=$license['id'];?>/renew">Renew</a>
                        <?php endif; ?>
                         / <a href="/license/<?=$license['id'];?>/remove" onclick="return confirm('Continue?');">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
