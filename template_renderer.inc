<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 //EN"
"http://www.w3.org/TR/html4/strict.dtd">

<html>
<head>
    <title><?php echo($rInfo['pageTitle']); ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <meta http-equiv="Cache-Control" content="no-cache">
    <link rel= "stylesheet" href= "style/libstats.css" type= "text/css" media="screen">
	<link rel= "stylesheet"  href= "style/print.css" type= "text/css" media= "print">
    <script type = "text/javascript" src = "js/formatFunctions.js"></script>
    <script type = "text/javascript" src = "js/libstatsFunctions.js"></script>
    <script type = "text/javascript" src = "js/prototype.js"></script>
    <script type = "text/javascript" src = "js/effects.js"></script>
    <script type = "text/javascript" src = "js/behaviour.js"></script>
    <script type = "text/javascript" src = "js/style.js"></script>
<?php
// check to see if calendar widget is enabled
if (CAL_WIDGET == TRUE) {
?>
<!-- add the following CSS for the calendars -->
<style type="text/css">@import url(addons/jscalendar-1.0/calendar-win2k-1.css);</style>
<script type="text/javascript" src="addons/jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" src="addons/jscalendar-1.0/lang/calendar-en.js"></script>
<script type="text/javascript" src="addons/jscalendar-1.0/calendar-setup.js"></script>
<?php } ?>
</head>
<body>
<?php require_once('DisplayFunctions.php'); ?>
<div id = "wrapper">
<?php include $rInfo['content'];?>
</div>
</body>
</html>
