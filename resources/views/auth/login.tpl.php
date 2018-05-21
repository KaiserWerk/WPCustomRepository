<h2>Login</h2>

<form method="post">
    <?php AuthHelper::generateCSRFInput(); ?>
    User: <input type="text" name="_login[username]"><br>
    PW: <input type="password" name="_login[password]"><br>
    <br>
    <input type="submit">
</form>