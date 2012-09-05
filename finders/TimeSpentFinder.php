<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class TimeSpentFinder extends Finder {

    var $cols = array('time_spent_id', 'time_spent', 'parent_list', 'description', 'examples');
    var $table = 'time_spent_options';
	var $joinTable = 'library_time_spent_options';
    var $joinCol = 'time_spent_id';
	var $joinCols = array('time_spent_id', 'library_id', 'list_order');

    function findByLibrary($libraryId = -1) {
        return parent::_findByLibrary(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
    }

    function getLast($clientAddr) {
        $query = 
        "SELECT
            {$this->joinCol}
        FROM
            questions
        WHERE
            client_ip = ?
        ORDER BY 
            question_id DESC
        LIMIT 1";

        return $this->db->getOne($query, array($clientAddr));
    }
	
	function findByLibraryID($libraryId) {
        $query = 
        "SELECT
			library_time_spent_options.library_id,
            library_time_spent_options.time_spent_id,
            library_time_spent_options.list_order,
			time_spent_options.time_spent
        FROM
            library_time_spent_options
		JOIN time_spent_options ON
			(library_time_spent_options.time_spent_id = time_spent_options.time_spent_id)
        WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function getDistinctList() {
		$query =
		"SELECT
			time_spent_id,
			time_spent
		FROM
			time_spent_options
		ORDER BY
			time_spent ASC";
		return $this->db->getAll($query);
	}

	function addOption($option_pk, $time_spent, $parent_pk, $description, $examples) {
        return parent::_addOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$time_spent,
			$parent_pk,
			$description,
			$examples);
    }
	
	function editOption($option_pk, $time_spent, $parent_pk, $description, $examples) {
        return parent::_editOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$time_spent,
			$parent_pk,
			$description,
			$examples);
    }

	function deleteOption($option_pk, $time_spent, $parent_pk, $description, $examples) {
        return parent::_deleteOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$time_spent,
			$parent_pk,
			$description,
			$examples);
    }
	
	function addBridgeItem($time_spent_id, $library_id) {
        return parent::_addBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $time_spent_id,
			$library_id);
    }	
	
	function removeBridgeItem($time_spent_id, $library_id) {
        return parent::_removeBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $time_spent_id,
			$library_id);
    }	
	
	function findMoverLibraryID($libraryId) {
        return parent::_findMoverLibraryID(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
	}
	
	function moveBridgeItemUp($time_spent_id, $library_id) {
        return parent::_moveBridgeItemUp(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $time_spent_id,
			$library_id);
	}
	
	function moveBridgeItemDown($time_spent_id, $library_id) {
        return parent::_moveBridgeItemDown(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $time_spent_id,
			$library_id);
	}
}
?>
