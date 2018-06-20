<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-7 col-sm-12">
            <h1 class="mt-5">Reset your password</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <form method="post" action="?_confirmation_token=<?=$token;?>">
                <?php AuthHelper::generateCSRFInput(); AuthHelper::generateHoneypotInput(); ?>
                <?php if (!isset($_GET['confirmation_token'])): ?>
                    <div class="form-group">
                        <label for="password1">Confirmation Token:</label>
                        <input type="text" name="confirmation_token" class="form-control" id="password1">
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label for="password1">New Password:</label>
                    <input type="password" name="_reset_reset[password1]" class="form-control" id="password1">
                </div>
                <div class="form-group">
                    <label for="password2">Repeat New Password:</label>
                    <input type="password" name="_reset_reset[password2]" class="form-control" id="password2">
                </div>
                <button type="submit" name="btn_reset_reset" class="btn btn-primary">Set New Password</button>
            </form>

        </div>
        <div class="col-md-5 col-sm-12">
            <h1 class="mt-5">Info</h1>
            <br>
            <p>
                <ul>
                    <li>Password minimum length: 12 characters</li>
                    <li>At least 1 uppercase and 1 lowercase character</li>
                    <li>At least 1 numerical character</li>
                </ul>
            </p>
        </div>
    </div>
</div>


