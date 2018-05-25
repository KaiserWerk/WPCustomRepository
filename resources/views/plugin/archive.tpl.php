<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Archive plugin</h1>
            <br>
            If you archive <strong><?php echo $plugin['plugin_name'] . ' v' . $plugin['version']; ?></strong>, it will no longer show up
            in the plugin list. Do you really want to archive this plugin?<br><br>
            <a href="/plugin/<?php echo $plugin['id']; ?>/archive?do" class="btn btn-warning">Yes, archive it</a>
        </div>
    </div>
</div>