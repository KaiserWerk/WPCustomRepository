<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <h1 class="mt-5">Info</h1>
            <br>
            <p>
                In case you forgot your password, can request a new one here by supplying your username or
                email address. An email with instructions will be sent to your inbox.
            </p>
        </div>
        <div class="col-md-6 col-sm-12">
            <h1 class="mt-5">Request a new password</h1>
            <?php Helper::getMessage(); ?>
            <form method="post">
                <?php
                AuthHelper::generateCSRFInput();
                AuthHelper::generateHoneypotInput();
                ?>
                <div class="form-group">
                    <label for="username">Username or Email:</label>
                    <input type="text" name="_reset_request[username]" class="form-control" id="username">
                </div>
                <button type="submit" name="btn_reset_request" class="btn btn-primary">Send the request</button>
            </form>

        </div>
        
    </div>
</div>


