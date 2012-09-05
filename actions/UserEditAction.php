<?php
require_once 'Action.php';

class UserEditAction extends Action {

	/**
	 * prepare information to save in database
	 * @result		= array containing information to insert/update in the table
	 *
	 */
	function perform() {
		// Don't set up default destination here. We're going to use the
		// userAdminFormAction to display the result page.

		// don't lose the db!	
		$db = $_REQUEST['db'];

		// where are we?	
		$uf = new UserFinder($db);
		$user = $uf->findById($_SESSION['userId']);
		$result['user'] = $user;

		$editUser = $this->parseUserFromForm();
		$saveResult = $uf->saveUser(
			$editUser['user_id'],
			$editUser['username'],
			md5($editUser['password']),	// wrap md5 around password
			$editUser['library_id'],
			$editUser['admin']);
		$_REQUEST['selUserId'] = $editUser['user_id'];
		$_REQUEST['saveResult'] = $saveResult;
		
		$act = new UserAdminFormAction();
		$result =  $act->perform();
		$result['saveResult'] = $saveResult;
		return $result;
	}


	function isAuthenticationRequired() {
		return true;
	}

	
	function isAdminRequired() {
		return true;
	}
	

	function parseUserFromForm() {
		$editUser = array();
		$userId = gpwd('user_id', 0);
		$username = gpwd('username', '');
		$password = gpwd('password', '');
		$library_id = gpwd('library_id');
		$admin = gpwd('admin', 0);

		if ($userId && is_numeric($userId)) {
			$editUser['user_id'] = $userId;
		}
		else {
			$editUser['user_id'] = null;
		}

		$editUser['username'] = $username;
		$editUser['password'] = trim($password);
		$editUser['library_id'] = $library_id + 0;

		if ($admin) {
		$editUser['admin'] = 1;
		} 
		else {
			$editUser['admin'] = 0;
		}
		return $editUser;
	}
}
?>
