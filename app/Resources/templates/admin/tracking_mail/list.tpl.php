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
                <?php if (count($mails) > 0): ?>
                    <?php foreach ($mails as $mail): ?>
                    <tr>
                        <td><?=$mail['confirmation_token'];?></td>
                        <td><?=$mail['recipient'];?></td>
                        <td><?=$mail['sent_at']?></td>
                        <td><?=$mail['token_used_at'];?></td>
                        <td><?=$mail['tracking_count'];?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center">No tracking mails found!</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>