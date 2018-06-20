<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Tracking Mail List</h1>
            <br>
            <table class="table table-condense table-bordered">
                <thead>
                <tr>
                    <th>Confirmation Token</th>
                    <th>Recipient</th>
                    <th>Sent at</th>
                    <th>Token used at</th>
                    <th>Tracking count</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($vars['mails'] as $mail) {
                        echo '<tr>';
                        echo '<td>'.$mail['confirmation_token'].'</td>';
                        echo '<td>'.$mail['recipient'].'</td>';
                        echo '<td>'.$mail['sent_at'].'</td>';
                        echo '<td>'.$mail['token_used_at'].'</td>';
                        echo '<td>'.$mail['tracking_count'].'</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>