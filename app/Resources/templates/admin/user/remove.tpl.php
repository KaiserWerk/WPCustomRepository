<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="mt-5">Remove User</h1>
            <?php Helper::getMessage(); ?>
            <br>
            <div class="row">

                <div class="col-md-12 col-sm-12 text-center">
                    <form action="/admin/user/<?=$id;?>/remove" method="post">
                        <?php AuthHelper::generateCSRFInput(); ?>

                        <p class="h5">Are you sure you want to permanently remove the following user account? <br><br><?=$user['username'];?></p>

                        <div class="form-group">
                            <br>
                            <button type="submit" name="btn_remove_user" class="btn btn-lg btn-danger">Remove user</button>
                            <a href="/admin/user/list" class="btn btn-lg btn-light">Back to list</a>
                        </div>
                    </form>
                </div>

            </div>

        </div>
    </div>
</div>