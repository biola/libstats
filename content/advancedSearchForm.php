<?php include('mainHeader.php');
require_once('DisplayFunctions.php');

$user = $rInfo['user'];
//$currentReport = $_GET["report_id"];
$origin = $rInfo['origin'];
//$report = $rInfo['reportList'];
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
								'*',
								false,
								false);
	
	locationBox.options[locationBox.options.length] = addAllLocs;
	
	
	var addAllLocs = new Option('--------------------------------',
								'',
								false,
								false);
	
	locationBox.options[locationBox.options.length] = addAllLocs;
	
	for (i = 0; i <= jsloc.length; i++) {
		if (jsloc[i] && jsloc[i]["library_id"] == library_id) {
			var temp = new Option(jsloc[i]['location_name'],
								  jsloc[i]['location_id'],
								  false,
								  false);
			locationBox.options[locationBox.options.length] = temp;
		}
	}

}

function select(id, value) {
    var selElement = document.getElementById(id);
    if (!selElement) { return; }
    
    for (var i = 0; i < selElement.options.length; i++) {
        if (selElement.options[i].value == value) {
            
            selElement.selectedIndex = i;
        }
    }
}

</script>

<form name="dates" method="get" action="advancedSearch.do" id="qForm">
<div id="toolbar">
<div class = "inputBox">
	<h5>Library</h5>
<?php echo getSelectPulldown('library_id', $libraryList, 'library_id', 'short_name', grwd('library_id', $rInfo['library_id']), 'onchange="findLocation(\'library_id\')"'); ?>
</div>
<div class ="inputBox">
	<h5>Location</h5>
<select id="location_id" name="location_id">
	<option value="*" selected>All Locations</option>
	<option value="">----------------------------</option>
	<?php foreach ($locationList as $list) {
			echo ('<option value="' . $list['location_id'] . '">' . $list['location_name'] . '</option>');
		}?>	
</select>
</div>
<div class = "inputBox">
    <h5>Initials</h5>
    <input type = "text" size = "5" name = "initials" id = "initials" value = "<?php echo urldecode(grwd('initials'));?>"/>
</div>
<div class = "inputBox">
	<h5>Start Date</h5>
	<input type="text" name="date1" id = "date1" class = "validDate" value = "<?php echo urldecode(grwd('date1'));?>"/>
</div>
<div class = "inputBox">
	<h5>End Date</h5>
	<input type="text" name="date2" id = "date2" class = "validDate" value = "<?php echo urldecode(grwd('date2'));?>" />
</div>
</div>
<div id="qBox">
	<h5>Containing Text:</h5>
	<input type="text" name="searchString" size="100px" value = "<?php echo urldecode(grwd('searchString'));?>" />
</div>
<div>
	<h5>Notes:</h5>
	<ul>
		<li>Use quotes for phrases: &quot;ISO 9000&quot;</li>
		<li>Use * for truncation: standard*</li>
		<li>Words shorter than three letters are not searchable.</li>
		<li>Dates can be of the forms:
		  <ul>
		      <li>Standard mm/dd/yy (6/15/05)</li>
		      <li>Standard with time (6/15/05 3:12 PM)</li>
		      <li>Time only (3:12 pm)</li>
		      <li>Text dates (June 15, 2005)</li>
		      <li>Relative dates (3 months ago)</li>
		      <li>Try other things; you'll see if they work.</li>
		  </ul>
        </li>
	</ul>
</div>
<div><input type="submit" value="Run Search"/></div>
</form>


<script type = "text/javascript">
findLocation('library_id');
<?php if (is_numeric(grwd('location_id'))) { ?>
select('location_id', <?php echo $_GET['location_id'] ?>);
<?php } ?>
</script>

<?php include 'footer.php'; ?>