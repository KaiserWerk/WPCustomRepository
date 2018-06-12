<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5">Login</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">
                <div class="col-md-6">
                    <form method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>
                        Username / Email: <input type="text" name="_login[username]" class="form-control"><br>
                        Password: <input type="password" name="_login[password]" class="form-control"><br>
                        <input type="submit" value="Login" class="btn btn-primary">
                    </form>
                </div>
                <div class="col-md-6">
                    You forgot your password? Click <a href="/resetting/request">here</a>.
                </div>
            </div>
            
        </div>
    </div>

