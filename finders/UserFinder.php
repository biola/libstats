<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */
 require_once 'DB.php';

class UserFinder extends Finder {
    var $cols = array('user_id', 'username', 'password', 'library_id', 'admin');
    // Default constructor

    var $ldapConn;
    
    function authenticate($username, $password) {
    	
    	global $ldapConfig;
    	
    	if ($result = $this->authenticateDB($username,$password)) {
    		return $result;
    	} else {
    		$this->ldapConn = @ldap_connect($ldapConfig['host'], $ldapConfig['port']);
    		if ($this->ldapConn) {
    			return $this->authenticateLDAP($username, $password);
    		} else {
    			return false;
    		}
    	}
    }
    
    function authenticateDB($username, $password) {
    	
        $query = 
        "SELECT 
            user_id 
        FROM 
            users 
        WHERE 
            active <> 0 AND username = ? AND password = ?";
        $result = $this->db->getOne($query, array($username, $password));
        return $result;
    }
    
    function authenticateLDAP($username, $password) {
    	global $ldapConfig;
    	
    	@ldap_set_options($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
    	@ldap_start_tls($conn);
    	$rdn = "uid=$username," . $ldapConfig['baseDN'];
    	$result = @ldap_bind($conn, $rdn, $passwd);
/*
  		if ($result) {
  			$this->saveUser(null, $username, $password, 1, 0);
  		}
 */
    	
    	@ldap_close($conn);
    	return $result;
    }
    
    function checkCookieCredentials($cookieVal) {
        // Check the database for this cookie
        $table = "cookie_logins";
        $query = 
        "SELECT cookie_login_id, user_id FROM cookie_logins WHERE cookie = ?";
        $result =  $this->db->getRow($query, array($cookieVal));
        if ($result != null) {
            $now = date('Y-m-d H:i:s');
            $fArr = array('date_last_used' => $now);
            $where = "cookie_login_id = {$result['cookie_login_id']}";
            $this->db->autoExecute(
                $table, 
                $fArr, 
                DB_AUTOQUERY_UPDATE,
                $where);
        }
        return $result;
    }

    function setCookieCredentials($cookieVal, $userId) {
        $table = "cookie_logins";
        $now = date('Y-m-d H:i:s');
        $fArr = array(
            'cookie' => $cookieVal,
            'user_id' => $userId+0,
            'date_last_used' => $now
        );
        $res = $this->db->autoExecute($table, $fArr, DB_AUTOQUERY_INSERT);
    }

    function clearCookieCredentials($cookieVal) {
        $query = 
        "DELETE FROM cookie_logins WHERE cookie = ?";
        $st = $this->db->prepare($query);
        $this->db->execute($st, array($cookieVal));
    }

    function findById($userId) {
        $users = $this->findUsers($userId);
        if (count($users) > 0) 
        {
            return $users[0];
        }
        return array(
            'user_id' => 0,
            'username' => '',
            'library_id' => 0,
            'active' => 0,
            'library_full_name' => '',
            'library_short_name' => '',
            'admin' => 0);
    }

    function findUsers($userId = NULL) {
        $query = 
        "SELECT 
            user_id, 
            username, 
            users.library_id,
            users.active,
            libraries.full_name AS library_full_name,
            libraries.short_name AS library_short_name,
			admin
        FROM users
        JOIN libraries USING (library_id)";
        
        $idArr = array();
        if ($userId !== NULL) {
            $idArr[] = $userId;
            $query .= " WHERE user_id = ?";
        }
        $query .= " ORDER BY username";
        return $this->db->getAll($query, $idArr);
    }
    
    /** Saves a user to the database -- creates if not already in db
      * 
      * @param userId       The ID of the user. If this is null, does an insert.
      *
      * @param username     The username for the user, used for login purposes. 
      *                     Must be unique, or the edit won't save.
      *
      * @param password     A new password for the user. If null or whitespace,
      *                     the password will not be changed.
      *
      * @param libraryId    The user's associated library ID. Must be numeric.
      *
      * @param admin        Should evaluate to true if the user is to have
      *                     admin rights.
      * @return a user id on success, or 0 on failure.
      */
    function saveUser(
        $userId,
        $username,
        $password,
        $libraryId,
        $admin)
    {
        $arr = array();
        $arr['username'] = $username;
        $arr['library_id'] = $libraryId;
        $arr['admin'] = 0;
        if ($admin) { $arr['admin'] = 1; }
        
        $password = trim($password);
        if ($password <> '') {
            $arr['password'] = $password;
        }
        
        // Now our array is constructed. Do an update if $userid is numeric,
        // do an insert if it's null and we have a password, fail otherwise.
        if (is_numeric($userId) && $userId > 0) {
            $whereClause = "user_id = $userId";
            $result = $this->db->autoExecute(
                "users", 
                $arr, 
                DB_AUTOQUERY_UPDATE,
                $whereClause);
            if (!DB::isError($result)) {
                return $userId;
            }
            return 0;
        }
        
        // Add a user, maybe.
        if ($userId == null && $password <> '') {
            $result = $this->db->autoExecute("users", $arr);
            if (DB::isError($result)) {
                return 0;
            }
            $result = $this->db->getOne("SELECT LAST_INSERT_ID()");
            return $result;
        }
        // If we've gotten here, it's an error.
        return 0;
    }
}
?>
