<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Add User</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <form method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input id="username" name="_add[username]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First name:</label>
                            <input id="first_name" name="_add[first_name]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name:</label>
                            <input id="last_name" name="_add[last_name]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input id="password" name="_add[password]" type="text" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input id="email" name="_add[email]" type="email" class="form-control" required>
                        </div>
                        <div class="checkbox">
                            Flags:<br>
                            <label for="locked"><input id="locked" name="_add[locked]" type="checkbox" value="1"> Locked</label> &nbsp;
                            <label for="admin"><input id="admin" name="_add[admin]" type="checkbox" value="1"> Admin</label> &nbsp;
                            <label for="send_notification"><input id="send_notification" name="_add[send_notification]" type="checkbox" value="1" checked> Send Notification</label> &nbsp;
                        </div>
                        <div class="form-group">
                            <br>
                            <button type="submit" name="btn_add_user" class="btn btn-primary">Add user</button>
                            <a href="/admin/user/list" class="btn">Back to list</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-sm-12">
                    <br>
                    Passwords should be at least 12 characters long and contain a mix of letters, numbers and special chars.
                </div>
            </div>

        </div>
    </div>
</div>


