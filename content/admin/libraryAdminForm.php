<?php include('content/mainHeader.php');
$library = $rInfo['library'];
$library_id = $rInfo['library_id'];
$libraryList = $rInfo['libraryList'];
$bridgeTableList = $rInfo['bridgeTableList'];
$adminTableList = $rInfo['adminTables'];
$parentTableList = $rInfo['parentTableList'];
$parent_table_data = $rInfo['parent_table_data'];
$parent_table = $parent_table_data['parent_table'];
$parent_pk = $parent_table_data['parent_pk'];
$descriptor = $parent_table_data['descriptor'];
?>
<script type = "text/javascript">

function isChosen(select) {
    if (select.value == "0") {
        alert("Please make a choice from the list.");
        return false;
    }
	else {
		return true;
	}
}


function validateForm(form) {
	if (isChosen(form.library_id) && isChosen(form.parent_table)) {
		return true;
	}
	return false;
}

</script>

<div id="adminForm">
    <ul id = "adminOptions">
    <li>Library Administration</li>

    <li><a href = "userAdminForm.do">User Administration</a></li>
    </ul>

	<div id="adminInput">
		<h4>Select a Library &amp; Table:</h4>
		<form action="libraryAdminForm.do" method="get" id="select_lib" onsubmit="return validateForm(this)">
			<select id="library_id" name="library_id" id="library_id">
				<option value="<?php echo($library_id); ?>" selected><?php echo($library); ?></option>
				<option value="0">----------------------------</option>
				<?php foreach ($libraryList as $name) {
					echo ('<option value="' . $name['library_id'] . '">' . $name['short_name'] . '</option>');
				}?>
			</select>
			<select id="parent_table" name="parent_table" id="parent_table">
				<option value="<?php echo($parent_table_data['parent_table']); ?>" selected><?php echo($parent_table_data['display_name']); ?></option>
				<option value="0">----------------------------</option>
				<?php foreach ($adminTableList as $table) {
					echo ('<option value="' . $table['parent_table'] . '">' . $table['display_name'] . '</option>');
				}?>
			</select>
			<input type="submit" value="Go" /><br />
			<a href="libraryEditForm.do">Add / Edit Libraries...</a>
		</form>

	</div>
	<div id="adminWrapper">
		<div id="optionBox">
			<form action="<?php echo($parent_table_data['edit_action_class']); ?>" method="post" id="libloc">
				<h4>Available <?php echo($parent_table_data['display_name']); ?></h4>
				<select id="all_locs" name="all" size="12">
				<?php foreach ($parentTableList as $list) {
					echo ('<option value="' . $list[$parent_pk] . '">' . $list[$descriptor] . '</option>');
				} ?>
				</select><br />
				<a href="optionAdminForm.do?table=<?php echo($parent_table_data['parent_table']); ?>">Add / Edit <?php echo($parent_table_data['display_name']); ?>...</a>
		</div>
		<div id="adminButtons">
				<input type="submit" name="add" value="Add ->"/>
				<input type="submit" name="remove" value="<- Remove"/>
		</div>
		<div id="visibleBox">
				<h4>Visible <?php echo($parent_table_data['display_name']); ?></h4>
				<select id="location_id" name="visible" size="12" >
				<?php foreach ($bridgeTableList as $list) {
					echo ('<option value="' . $list[$parent_pk] . '">' . $list[$descriptor] . '</option>');
				}?>	
				</select>
		</div>
				<input type="hidden" name="library_id" value="<?php echo($library_id); ?>"/>
				<input type="hidden" name="library" value="<?php echo($library); ?>" />
				<input type="hidden" name="bridge_table" value="<?php echo($bridgeTableList); ?>"/>
				<input type="hidden" name="parent_table" value="<?php echo($parent_table_data['parent_table']); ?>"/>
				<input type="hidden" value="<?php echo( $bridgeTableList); ?>"/>
		<div id="adminButtons">
				<input type="submit" name="up" value="Move Up" />
				<input type="submit" name="down" value="Move Down" />
		</div>
			</form>
	</div>
	<div id="adminClear">
	</div>
</div>