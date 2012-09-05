<?php include('noAuthHeader.php'); ?>

<form action = "login.do" method = "post" name = "loginForm" id = "loginForm">
<div>
    <label for = "username">Username</label>
    <input type = "text" name = "username" id = "username"
        value = "<?php echo($rInfo['username']); ?>">
</div>
<div>
    <label for = "password">Password</label>
    <input type = "password" name = "password" id = "password">
</div>
<div>
    <input type = "submit" name = "gobutton" value = "Log In" class = "aligned" />
</div>
<div>
<h4 style = "padding-top: 1em;">Need help?</h4>
<p>If you're having trouble logging in, contact your supervisor for
login information, or <a href="mailto:<?php echo(DEV_EMAIL); ?>"><?php echo(DEV_NAME); ?>
</a> if that's not working out for you.
</p>
</div>
<br style = "clear: both;" />
</form>
