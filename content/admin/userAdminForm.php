<?php
include('content/mainHeader.php');
$selUser = $rInfo['selUser'];
require_once('DisplayFunctions.php');
?>

<div id="adminForm">
    <ul id = "adminOptions">
    <li><a href = "libraryAdminForm.do">Library Administration</a></li>

    <li>User Administration</li>
    </ul>
    
	<div id="adminInput">
        <h4>Select a User</h4>
        <form method = "get" action = "userAdminForm.do">
            <select name = "selUserId">
                <option value = "new">New user</option>
                <option value = "new"> -- </option>
            <?php foreach ($rInfo['userList'] as $curUser) { ?>
                <option value = "<?php echo( $curUser['user_id'] ); ?>"
                    <?php if ($curUser['user_id']==$rInfo['selUser']['user_id'])
                        echo 'selected = "selected"';
                    ?> >
                    <?php echo( $curUser['username'] ); ?>
                </option>
            <?php } ?>
            </select>
            <input type = "submit" name = "gobutton" value = "Go">
        </form>
	</div>
	<div id="userAdminWrapper">
	   <h3>User Information</h3>
	   <?php 
	   if (array_key_exists('saveResult', $rInfo)) {
	       if ($rInfo['saveResult']) { ?>
	   <div class = "successMessage">
	       Settings saved
	   </div>
	   <?php
	       } else { ?>
	   <div class = "failureMessage">
	       Save failed
	   </div>
	   <?php
	       }
	   }
	   ?>
	   <form action = "userEdit.do" method = "post">
	       <input type = "hidden" name = "user_id" 
	           value = "<?php echo($selUser['user_id']); ?>" />
	       <div>
	           <label for = "username">Username</label> 
	           <input type = "text" name = "username" id = "username" 
	               value = "<?php echo($selUser['username']); ?>" />
	       </div>
	       <div>
	           <label for = "password">Password</label> 
	           <input type = "text" name = "password" id = "password" 
	               value = "" />
	           (Leave blank if you don't want to change it)
	       </div>
	       <div>
	           <label for = "library_id">Library</label> 
	            <?php echo getSelectPulldown(
                    "library_id", 
                    $rInfo['libraryList'],
                    'library_id',
                    'short_name',
                    $selUser['library_id']); ?>
	       </div>
	       <div>
	           <input type = "checkbox" name = "admin" id = "admin" value="true"
	               <?php if ($selUser['admin']) echo 'checked = "checked"';?> />
	           <label for = "admin" class = "noPos">Admin</label> 
	       </div>
	       <div>
                <input type = "submit" name = "save" value = "Save" />
	       </div>
	       
	       
	   </form>
    </div>
</div>