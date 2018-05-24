<?php
$errors = array(
    'edit_success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Edited successfully!</div>',
    'add_success' => '<br>
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-check"></span></strong> Added successfully!</div>',
    'missing_input' => '<br>
            <div class="alert alert-info alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-info-circle"></span></strong> Missing input!</div>',
    'unknown_error' => '<br>
            <div class="alert alert-danger alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong><span class="fa fa-warning"></span></strong> Unknown error!</div>',
);

?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">License List</h1>
            <?php
            if (isset($_GET['e'])) {
                $e = $_GET['e'];
                if (!array_key_exists($e, $errors)) {
                    $e = 'unknown_error';
                }
                echo $errors[ $e ];
            }
            ?>
            <br>
            <table class="table table-condense table-bordered">
                <thead>
                <tr>
                    <th>License User</th>
                    <th>License Key</th>
                    <th>License Host</th>
                    <th>Valid until</th>
                    <th>Renewals</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($licenses as $license) {
                    echo '<tr>';
                    echo '<td>'.$license['license_user'].'</td>';
                    echo '<td><textarea class="form-control-plaintext" onfocus="this.select();" rows="3" cols="50">'.$license['license_key'].'</textarea></td>';
                    echo '<td>'.$license['license_host'].'</td>';
                    echo '<td>'.(new \DateTime($license['valid_until']))->format('Y-m-d').'</td>';
                    echo '<td>'.$license['renewals'].'</td>';
                    
                    echo '<td>';
                    $soon = new \DateTime($license['valid_until']);
                    $now = new \DateTime('+12 month');
                    if ($soon < $now) {
                        echo '<a href="/license/renew?id='.$license['id'].'">Renew</a> ';
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
