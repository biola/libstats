<div id = "header">
    <div id = "locationInfo">
         <?php if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn']) { ?>
        <div>
        <?php echo($rInfo['user']['library_full_name']); ?>
        | <a href="javascript:window.close()">Close</a>
		</div>
		<?php } ?>
    </div>
	<h1><?php echo($rInfo['pageTitle']); ?></h1>
    <div style = "clear: both;"> </div>
</div>

