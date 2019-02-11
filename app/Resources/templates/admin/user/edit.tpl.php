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
                        <input type="hidden" name="id" value="<?=$id;?> ">
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input id="username" name="_edit[username]" type="text" class="form-control" value="<?=$user['username'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="first_name">First name:</label>
                            <input id="first_name" name="_edit[first_name]" type="text" class="form-control" value="<?=$user['first_name'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="last_name">Last name:</label>
                            <input id="last_name" name="_edit[last_name]" type="text" class="form-control" value="<?=$user['last_name'];?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input id="password" name="_edit[password]" type="text" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="email">E-Mail:</label>
                            <input id="email" name="_edit[email]" type="email" class="form-control" value="<?=$user['email'];?>" required>
                        </div>
                        <div class="form-group">
                            <label>Sex:</label><br>
                            <label class="radio-inline"><input name="_edit[sex]" type="radio" value="m"<?php if($user['sex'] === 'm') echo ' checked'; ?>> Male</label> &nbsp;
                            <label class="radio-inline"><input name="_edit[sex]" type="radio" value="f"<?php if($user['sex'] === 'f') echo ' checked'; ?>> Female</label> &nbsp;
                        </div>
                        <div class="checkbox">
                            Flags:<br>
                            <label for="locked"><input id="locked" name="_edit[locked]" type="checkbox" value="1"<?php if($user['locked'] == '1') echo ' checked'; ?>> Locked</label> &nbsp;
                            <label for="admin"><input id="admin" name="_edit[admin]" type="checkbox" value="1"<?php if($user['admin'] == '1') echo ' checked'; ?>> Admin</label> &nbsp;
                        </div>
                        <div class="form-group">
                            <br>
                            <button type="submit" name="btn_edit_user" class="btn btn-primary">Save changes</button>
                            <a href="/admin/user/list" class="btn">Back to list</a>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 col-sm-12">
                    <br>
                    Passwords should be at least 12 characters long and contain a mix of uppercase and lowercase letters, numbers and special chars.
                </div>
            </div>

        </div>
    </div>
</div>


