<?php
    // Better debugging; can't cache the contents of this page
    ini_set("display_errors", 1);
    header("Cache-Control: no-cache");


$db = $_REQUEST['db'];
var_dump($_REQUEST);

$lookupField = isset($_REQUEST['lookupField']) ? $_REQUEST['lookupField'] : '';
//$datestr = isset($_REQUEST['datestr']) ? $_REQUEST['datestr'] : '';
$lookupField = 3;

$locationFinder = new LocationFinder($db);
$locations =
	$locationFinder->findByLibrary($lookupField);
var_dump($locations);

?>

<html><head></head>
<?php echo("<body onload=\"parent.setOption('$option', '$parsedDateStr', '$success');\">"); ?>
<?php
    //debugging
	//echo ("<script type=\"text/javascript\">alert(\"$parserDateStr\");</script");
    //echo ('PARSED: ' . $parsedDateStr);
	//echo "MONKEY";
?>
</body></html>