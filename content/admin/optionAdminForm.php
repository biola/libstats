<?php
include('content/mainHeader.php');
$adminTableList = $rInfo['adminTables'];
$parent_table_data = $rInfo['parent_table_data'];
$parentTableList = $rInfo['parentTableList'];
$parent_pk = $parent_table_data['parent_pk'];
$descriptor = $parent_table_data['descriptor'];
$everything = $rInfo['everything'];
$library = $rInfo['library'];
$library_id = $rInfo['library_id'];
if (isset($_POST['table'])) {
	$target = $_POST['table'];
}
elseif (isset($_GET['table'])) {
	$target = $_GET['table'];
}
else { $target = ""; }
//var_dump($target);
?>

<script type = "text/javascript">
var bridgeTableData;
var bridgeTableData = new Array();

<?php 
	for($i=0; $i<count($everything); $i++) {
		$item = $everything[$i];
?>
	currElement = new Array();
	currElement["parent_pk"] = <?php echo($item[$parent_pk]); ?>;
	currElement["descriptor"] = "<?php echo($item[$descriptor]); ?>";
	currElement["parent_list"] = <?php echo($item['parent_list']); ?>;
	currElement["description"] = "<?php echo($item['description']); ?>";
	currElement["examples"] = "<?php echo(mysql_real_escape_string($item['examples'])); ?>";
	bridgeTableData[<?php echo($item[$parent_pk]); ?>] = currElement;

<?php
}
?>

function echoOption(currentOption) {
    var optionBox = document.getElementById(currentOption);
	var parent_pk = optionBox.options[optionBox.selectedIndex].value;	
	var bridge_option_pk = document.getElementById("option_pk");
	bridge_option_pk.value = bridgeTableData[parent_pk]["parent_pk"];	
	var bridge_descriptor = document.getElementById("name");
	bridge_descriptor.value = bridgeTableData[parent_pk]["descriptor"];
	var bridge_parent_list = document.getElementById("all_options");
	bridge_parent_list.value = bridgeTableData[parent_pk]["parent_list"];
	var bridge_description = document.getElementById("description");
	bridge_description.value = bridgeTableData[parent_pk]["description"];
	var bridge_examples = document.getElementById("examples");
	bridge_examples.value = bridgeTableData[parent_pk]["examples"];
}

function newOption() {
	var optionBox = document.getElementById('all_locs');
	var addOption = new Option('New Option',
								'0',
								false,
								false);
	
	updateBridge = new Array();
	updateBridge["parent_pk"] = "0";
	updateBridge["descriptor"] = "New Option";
	updateBridge["parent_list"] = "0";
	updateBridge["description"] = "";
	updateBridge["examples"] = ""; 

	bridgeTableData[0] = updateBridge;
	
	optionBox.options[optionBox.options.length] = addOption;
	optionBox.selectedIndex = optionBox.options.length -1;

	var optionName = document.getElementById('name');
	var all_locs = "0";
	echoOption('all_locs');
	optionName.focus();
	optionName.select();
}
</script>
<div id="adminForm">
	<div id="adminInput">
		<form action="optionAdminForm.do" method="get">
			<h4>Select a Table:</h4>
			<select id="parent_table" name="table" id="table">
				<option value="<?php echo($parent_table_data['parent_table']); ?>" selected><?php echo($parent_table_data['display_name']); ?></option>
				<option value="">----------------------------</option>
				<?php foreach ($adminTableList as $table) {
					echo ('<option value="' . $table['parent_table'] . '">' . $table['display_name'] . '</option>');
				}?>
			</select>
			<input type="submit" value="Go"/>
			<a href="<?php echo('libraryAdminForm.do?table=' . $target); ?>">Return to Admin</a>
		</form>
	</div>
	<div id="adminWrapper">
		<div id="optionBox">
			<h3><?php echo($parent_table_data['display_name']); ?></h3>
			<h4>Select Option:</h4>
			<select id="all_locs" name="all" size="12" onchange = "echoOption('all_locs');">
			<?php foreach ($parentTableList as $list) {
				echo ('<option value="' . $list[$parent_pk] . '">' . $list[$descriptor] . '</option>');
			} ?>
			</select><br />
			<div id="adminButtons2">
				<input type="button" id="new" value="New" onClick="newOption();"/>
			</div>
		</div>
		<div id=optionAdmin>
			<form action="addOption.do" method="post" id="addOption">
				<input type="hidden" id="option_pk" name="option_pk" />
				<h4>Name:</h4><input type="text" name="option" id="name" />
				<h4>Parent Class:</h4>
				<select id="all_options" name="parent_pk"/>
				<?php
				echo ('<option value="0" selected>None</option>');
				echo ('<option value="">----------------------------</option>');
				foreach ($parentTableList as $list) {
					echo ('<option value="' . $list[$parent_pk] . '">' . $list[$descriptor] . '</option>');
				}?>	
				</select>
				<h4>Description:</h4>
				<textarea rows="5" cols="50" name="description" id="description"></textarea>
				<h4>Examples:</h4>
				<textarea rows="5" cols="50" name="examples" id="examples"/></textarea>
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