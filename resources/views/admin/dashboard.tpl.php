<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Admin Dashboard</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>Tools:</h4>
                    <ul>
                        <li><a href="/admin/tools/mail-message">Send test mail</a></li>
                        <li><a href="/admin/tools/stride-message">Send test Stride message</a></li>
                        <li><a href="/admin/tools/hipchat-message">Send test HipChat message</a></li>
                        <li><a href="/admin/tools/slack-message">Send test Slack message</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Basic Statistics</h4>
                    <ul>
                        <li><?php echo $vars['user_count']; ?> User(s)</li>
                        <li><?php echo $vars['plugin_base_count']; ?> Base Plugin(s)</li>
                        <li><?php echo $vars['plugin_version_count']; ?> Plugin Version(s)</li>
                        <li><?php echo $vars['theme_base_count']; ?> Base Theme(s)</li>
                        <li><?php echo $vars['theme_version_count']; ?> Theme Version(s)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


