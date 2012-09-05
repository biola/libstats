<?php include('mainHeader.php');
$user = $rInfo['user'];
$currentReport = $_GET["report_id"];
$origin = $rInfo['origin'];
$report = $rInfo['reportList'];
$library = $rInfo['library'];
$libraryID = $rInfo['library_id'];
$libraryList = $rInfo['libraryList'];
$locationList = $rInfo['locationList'];
?>
<script type = "text/javascript">
var jsloc = new Array();
<?php 

foreach($locationList as $Location) {
?>
	var currloc = new Array();
	currloc["library_id"]= "<?php echo($Location['library_id']); ?>";
	currloc["location_id"]= "<?php echo($Location['location_id']); ?>";
	currloc["location_name"]= "<?php echo($Location['location_name']); ?>";
	jsloc[jsloc.length] = currloc;
<?php
}
?>

function findLocation(libraryElementName) {
    var libraryBox = document.getElementById(libraryElementName);
	var library_id = libraryBox.options[libraryBox.selectedIndex].value;
	//alert(library_id);
	//alert(jsloc[0]['library_id']);
	var locationBox = document.getElementById("location_id");
	locationBox.length = 0;

	var addAllLocs = new Option('All Locations',
								'',
								false,
								false);
	
	locationBox.options[locationBox.options.length] = addAllLocs;
	
	
	var addAllLocs = new Option('--------------------------------',
								'',
								false,
								false);
	
	locationBox.options[locationBox.options.length] = addAllLocs;
	
	for (i = 0; i <= jsloc.length; i++) {
		if (jsloc[i]["library_id"] == library_id) {
			var temp = new Option(jsloc[i]['location_name'],
								  jsloc[i]['location_id'],
								  false,
								  false);
			locationBox.options[locationBox.options.length] = temp;
		}
	}

}

</script>

<h3>You have chosen this report:</h3>

<div class="reports">
    <div class="report">
				<h3><?php echo($rInfo['reportList']['name']); ?></h3>
        <div id="reportDescription"><?php echo($rInfo['reportList']['desc']); ?></div>
    </div>
</div>
<form name="dates" method="get" action="reportReturn.do" id="qForm">
<div>
Begin Date: <input type="text" name="date1" id = "date1" class = "validDate" />
<?php
// if calendar widget is enabled, display button
if (CAL_WIDGET == TRUE) {
	echo "<button id=\"trigger1\">...</button>";
}
?>
End Date: <input type="text" name="date2" id = "date2" class = "validDate" />
<?php
// if calendar widget is enabled, display button
if (CAL_WIDGET == TRUE) {
	echo "<button id=\"trigger2\">...</button>";
}
?>
<br /><br />
Library:

<select id="library_id" onchange = "findLocation('library_id');" name="library_id">
	<option value="<?php echo($libraryID); ?>" selected><?php echo($library); ?></option>
	<option value="">All Libraries</option>
	<option value="">----------------------------</option>
	<?php foreach ($libraryList as $name) {
			echo ('<option value="' . $name['library_id'] . '">' . $name['short_name'] . '</option>');
		}?>
</select>
<?php echo( "Location: "); ?>
<select id="location_id" name="location_id">
	<option value="*" selected>All Locations</option>
	<option value="">----------------------------</option>
	<?php foreach ($locationList as $list) {
			echo ('<option value="' . $list['location_id'] . '">' . $list['location_name'] . '</option>');
		}?>	
</select>
<script type = "text/javascript">
	bodyElement = document.getElementsByTagName('body')[0];
	bodyElement.onload = findLocation('library_id');
</script>
<br />
<input type="hidden" name="report_id" value="<?php echo($rInfo['report_id']); ?>"/>
<input type="submit" value="Run Report"/>
</div>
</form>

<?php
// if calendar widget is enabled, display javascript to make it work
if (CAL_WIDGET == TRUE) {
?>
<!-- this is for the calendar widget -->
<script type="text/javascript">
 Calendar.setup(
  {
   inputField  : "date1",              // ID of the input field
   ifFormat    : "%m/%d/%Y %I:%M %p",  // the date format
   button      : "trigger1",           // ID of the button
   showsTime   : "true",               // show time
   timeFormat  : "12"                  // set time to 12 hours, not 24
  }
 );

 Calendar.setup(
 {
   inputField  : "date2",              // ID of the input field
   ifFormat    : "%m/%d/%Y %I:%M %p",  // the date format
   button      : "trigger2",           // ID of the button
   showsTime   : "true",               // show time
   timeFormat  : "12"                  // set time to 12 hours, not 24
  }
 );
</script>
<?php } ?>

<script type = "text/javascript">
var opts = checkCookies();
setDisplayCheckboxes(opts);
fixInitials();
fixQuestions();
</script>

<?php include 'footer.php'; ?>
