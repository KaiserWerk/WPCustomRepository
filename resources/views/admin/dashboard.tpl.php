

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Admin Dashboard</h1>
            <br>
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <h4>Tools:</h4>
                    <ul>
                        <li><a href="/admin/tools/test_mail_message">Send test mail</a></li>
                        <li><a href="/admin/tools/test_stride_message">Send test Stride message</a></li>
                        <li><a href="/admin/tools/test_hipchat_message">Send test HipChat message</a></li>
                        <li><a href="/admin/tools/test_slack_message">Send test Slack message</a></li>
                    </ul>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>Basic Statistics</h4>
                    <ul>
                        <li><?php echo $user_count; ?> User(s)</li>
                        <li><?php echo $plugin_distinct_count; ?> Distinct Plugin(s)</li>
                        <li><?php echo $plugin_count; ?> Plugin Version(s)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>


