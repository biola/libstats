<?php
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class Finder {
    var $db;
    function Finder($db) {
        $this->db = $db;
    }

    function _findByLibrary(
        $table,
        $columns,
        $joinCol,
        $libraryId = -1)
    {
        $libraryId += 0;
        if (!is_int($libraryId)) { $libraryId = -1; }

        // disambiguate; we always want to select from $table
        foreach ($columns as $index=>$col) {
            $columns[$index] = "$table.$col";
        }
        $colStr = implode(", ", $columns);

        // if we have a -1 library id, this is trivial... list everything
        if ($libraryId == -1) {
            $query = 
                "SELECT $colStr
                FROM $table";
        } 
        else {
            // In this case, we need to join the library prefx table
            $query = 
                "SELECT SQL_CACHE $colStr
                FROM $table
                JOIN library_$table USING ($joinCol)
                WHERE library_id = $libraryId
                ORDER BY list_order";
        }
        $results = $this->db->getAll($query);
        return $results;
    }
	
	function _addOption(
        $table,
        $columns,
        $joinCol,
		$option_pk, //$columns[0]
		$name, //$columns[1]
		$parent_pk, //$columns[2]
		$description, //$columns[3]
		$examples //$columns[4]
		) {  
        
		// Just add it... it should be easy ;-)
		/* 01-05-2009
		removed 	$columns[0] => '' from the array below.  Causes a problem passing
		a NULL value to an auto increment field.
		*/
      $field_values = array(
			$columns[1] => $name,
			$columns[2] => $parent_pk,
			$columns[3] => $description,
			$columns[4] => $examples
		);
		
		return $this->db->autoExecute(
            $table,
            $field_values,
            DB_AUTOQUERY_INSERT);
    }
	
	function _editOption(
        $table,
        $columns,
        $joinCol,
		$option_pk, //$columns[0]
		$name, //$columns[1]
		$parent_pk, //$columns[2]
		$description, //$columns[3]
		$examples //$columns[4]
		) {        
		
		// Edit with PEAR...
		$field_values = array(
			$columns[1] => $name,
			$columns[2] => $parent_pk,
			$columns[3] => $description,
			$columns[4] => $examples
		);
		$whereClause = ($columns[0] . '= ' . $option_pk);
		
		return $this->db->autoExecute(
            $table,
            $field_values,
            DB_AUTOQUERY_UPDATE, $whereClause);
	}

	function _deleteOption(
        $table,
        $columns,
        $joinCol,
		$option_pk, //$columns[0]
		$name, //$columns[1]
		$parent_pk, //$columns[2]
		$description, //$columns[3]
		$examples //$columns[4]
		) {
		
		// Delete with PEAR...
		$kill =
		'DELETE FROM ' . $table .
		'WHERE ' . $columns[0] . ' = ' . $option_pk;
		
		$result = $this->db->query($kill);
		return $result;
	}
	
	function _addBridgeItem(
		$table,
		$columns,
		$option_pk, //$columns[0]
		$library_id //$columns[1]
		) {
      
      /* 01-05-2009
		removed 	$columns[2] => '' from the array below.  Causes a problem passing
		a NULL value to an auto increment field.
		*/
		$field_values = array(
			$columns[0] => $option_pk,
			$columns[1] => $library_id,
		);
		
		return $this->db->autoExecute(
			$table,
			$field_values,
			DB_AUTOQUERY_INSERT);
	}
	
	function _removeBridgeItem(
		$table,
		$columns,
		$option_pk, //$columns[0]
		$library_id //$columns[1]
		) {
		
		$kill =
		'DELETE FROM ' . $table .
		' WHERE ' . $columns[0] . ' = ' . $option_pk .
		' AND ' . $columns[1] . ' = ' . $library_id;
		
		$result = $this->db->query($kill);
		return $result;
	}

	function _findMoverLibraryID(
        $table,
        $columns,
        $joinCol,
        $libraryId) {
    	$query = 
        "SELECT *
        FROM
            library_" . $table .
        " WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function _moveBridgeItemUp(
		$table,
		$cols,
		$joinTable,
		$joinCol,
		$joinCols,
		$option_pk, //columns[0]
		$library_id //columns[1]
		) {
		
		$order = $this->_findMoverLibraryID($table, $cols, $joinCol, $library_id);
		$depth = sizeof($order);
		
		for ($i=0; $i<$depth;) {
			$order[$i]['list_order'] = $i;
			$i++;
		}

		for($i=0; $i<$depth;) {
			if($order[$i][$joinCol] == $option_pk && $i-1 != -1) {
				$order[$i]['list_order'] = $i-1;
				$order[$i-1]['list_order'] = $i;
			}
			$i++;
		}
		
		foreach($order as $update) {
			$table_name = $joinTable;
			$field_values = array(
				'list_order' => $update['list_order']			
			);
			$whereClause = ('library_id = ' . $update['library_id'] .
							' AND ' . $joinCol . ' = ' . $update[$joinCol]);
			$result = $this->db->autoExecute($table_name, $field_values,
								DB_AUTOQUERY_UPDATE, $whereClause);
		}					
		return $result;
	}
	
	function _moveBridgeItemDown(
		$table,
		$cols,
		$joinTable,
		$joinCol,
		$joinCols,
		$option_pk, //columns[0]
		$library_id //columns[1]
		) {
		
		$order = $this->_findMoverLibraryID($table, $cols, $joinCol, $library_id);
		$depth = sizeof($order);
		$stopdepth = $depth +1;
		//echo $stopdepth;
		for ($i=0; $i<$depth;) {
			$order[$i]['list_order'] = $i;
			$i++;
		}
		//var_dump($loc_order);
		for($i=0; $i<$depth;) {
			if($order[$i][$joinCol] == $option_pk && $i+1 != $depth) {
				$order[$i]['list_order'] = $i+1;
				$order[$i+1]['list_order'] = $i;
			}
			$i++;
		}
		//var_dump($loc_order);
		foreach($order as $update) {
			$table_name = $joinTable;
			$field_values = array(
				'list_order' => $update['list_order']			
			);
			$whereClause = ('library_id = ' . $update['library_id'] .
							' AND ' . $joinCol . ' = ' . $update[$joinCol]);
		$result = $this->db->autoExecute($table_name, $field_values,
								DB_AUTOQUERY_UPDATE, $whereClause);
		}					
		
		return $result;
	}
}
?>
