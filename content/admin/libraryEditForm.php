<?php
include('content/mainHeader.php');
$adminTableList = $rInfo['adminTables'];
$parent_table_data = $rInfo['parent_table_data'];
$parent_pk = $parent_table_data['parent_pk'];
$descriptor = $parent_table_data['descriptor'];
$everything = $rInfo['everything'];
?>

<script type = "text/javascript">
var bridgeTableData;
var bridgeTableData = new Array();

<?php 
	for($i=0; $i<count($everything); $i++) {
		$item = $everything[$i];
?>
	currElement = new Array();
	currElement["library_id"] = <?php echo($item['library_id']); ?>;
	currElement["short_name"] = "<?php echo($item['short_name']); ?>";
	currElement["full_name"] = "<?php echo($item['full_name']); ?>";
	bridgeTableData[<?php echo($item['library_id']); ?>] = currElement;

<?php
}
?>

function echoOption(currentOption) {
    var optionBox = document.getElementById(currentOption);
	var library_id = optionBox.options[optionBox.selectedIndex].value;	
	var bridge_option_pk = document.getElementById("option_pk");
	bridge_option_pk.value = bridgeTableData[library_id]["library_id"];	
	var bridge_descriptor = document.getElementById("shortname");
	bridge_descriptor.value = bridgeTableData[library_id]["short_name"];
	var bridge_description = document.getElementById("fullname");
	bridge_description.value = bridgeTableData[library_id]["full_name"];
}

function newOption() {
	var optionBox = document.getElementById('all_locs');
	var addOption = new Option('New Library',
								'0',
								false,
								false);
	
	updateBridge = new Array();
	updateBridge["library_id"] = "0";
	updateBridge["short_name"] = "New Library";
	updateBridge["full_name"] = ""; 

	bridgeTableData[0] = updateBridge;
	
	optionBox.options[optionBox.options.length] = addOption;
	optionBox.selectedIndex = optionBox.options.length -1;

	var optionName = document.getElementById('shortname');
	var all_locs = "0";
	echoOption('all_locs');
	optionName.focus();
	optionName.select();
}
</script>
<div id="adminForm">
	<div id="adminInput">
		<a href="<?php echo( 'libraryAdminForm.do'); ?>">Return to Admin</a>
	</div>
	<div id="adminWrapper">
		<div id="optionBox">
			<h3><?php echo($parent_table_data['display_name']); ?></h3>
			<h4>Select Library:</h4>
			<select id="all_locs" name="all" size="12" onchange = "echoOption('all_locs');">
			<?php foreach ($everything as $list) {
				echo ('<option value="' . $list[library_id] . '">' . $list[short_name] . '</option>');
			} ?>
			</select><br />
			<div id="adminButtons2">
				<input type="button" id="new" value="New" onClick="newOption();"/>
			</div>
		</div>
		<div id=optionAdmin>
			<form action="addLibrary.do" method="post" id="addOption">
				<input type="hidden" id="option_pk" name="option_pk" />
				<h4>Short Name:</h4><input type="text" size="67" name="shortname" id="shortname" />
				<h4>Full Name:</h4>
				<textarea rows="2" cols="50" name="fullname" id="fullname"></textarea>
				<input type="hidden" name="parent_finder" value="<?php echo($parent_table_data['parent_finder']); ?>"/>
				<input type="hidden" name="parent_table" value="<?php echo($parent_table_data['parent_table']); ?>"/>
				<input type="hidden" name="library" value="<?php echo($library ); ?>"/>
				<input type="hidden" name="library_id" value="<?php echo($library_id ); ?>"/>
			<div id="adminButtons2">
				<input type="submit" name="save" value="Save" />
			</div>
			</form>
		</div>
			<div id="adminClear">
			</div>
		</div>
</div>