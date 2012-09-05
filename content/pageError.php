<?php include "noAuthHeader.php"; ?>

<img src = "images/kitten.jpg" class = "leftFloater"/> 
<h2>I messed up and I'm sorry.</h2>

<p>Most likely, you're seeing this message because <?php echo(DEV_NAME); ?> made a 
programming mistake somewhere. Rest assured that the server has already 
sent <?php echo(DEV_3OPN); ?> 
a message detailing the error of <?php echo(DEV_PPN); ?> ways in far more detail
than you would believe, and that <?php echo(DEV_3SPN); ?>'ll get on fixing it as soon 
as possible.
</p>
<p>If you want, feel free to <a href = "mailto:<?php echo(DEV_EMAIL); ?>">
send <?php echo(DEV_3OPN); ?> an email</a>, especially if this is the first time you've 
tried something or if there's just been an upgrade. And if you're feeling 
adventurous, you're more than welcome to <a href = "questionAddForm.do">
re-enter the database</a>, where the problem could already be fixed. 
You might also have to <a href="logout.do">log out</a> if the server is really confused. Ghosts lurk these machines.
</p>
<h4 style="clear: both;">Support contact information:</h4>
<p>
<?php echo(DEV_NAME); ?><br />
Email: <a href="mailto:<?php echo(DEV_EMAIL); ?>"><?php echo(DEV_EMAIL); ?><br />
</p>

<div id = "footer">
</div>