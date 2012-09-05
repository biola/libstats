<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class QuestionTypeFinder extends Finder {
    var $cols = array('question_type_id', 'question_type', 'parent_list', 'description', 'examples');
    var $table = 'question_types';
	var $joinTable = 'library_question_types';
    var $joinCol = 'question_type_id';
	var $joinCols = array('question_type_id', 'library_id', 'list_order');

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
			library_question_types.library_id,
            library_question_types.question_type_id,
            library_question_types.list_order,
			question_types.question_type
        FROM
            library_question_types
		JOIN question_types ON
			(library_question_types.question_type_id = question_types.question_type_id)
        WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function getDistinctList() {
		$query =
		"SELECT
			question_type_id,
			question_type
		FROM
			question_types
		ORDER BY
			question_type ASC";
		return $this->db->getAll($query);
	}
	
	function addOption($option_pk, $question_type, $parent_pk, $description, $examples) {
        return parent::_addOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_type,
			$parent_pk,
			$description,
			$examples);
    }

	function editOption($option_pk, $question_type, $parent_pk, $description, $examples) {
        return parent::_editOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_type,
			$parent_pk,
			$description,
			$examples);
    }

	function deleteOption($option_pk, $question_type, $parent_pk, $description, $examples) {
        return parent::_deleteOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_type,
			$parent_pk,
			$description,
			$examples);
    }
	
	function addBridgeItem($question_type_id, $library_id) {
        return parent::_addBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $question_type_id,
			$library_id);
    }			
	
	function removeBridgeItem($question_type_id, $library_id) {
        return parent::_removeBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $question_type_id,
			$library_id);
    }	
	
	function findMoverLibraryID($libraryId) {
        return parent::_findMoverLibraryID(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
	}
	
	function moveBridgeItemUp($question_type_id, $library_id) {
        return parent::_moveBridgeItemUp(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $question_type_id,
			$library_id);
	}
	
	function moveBridgeItemDown($question_type_id, $library_id) {
        return parent::_moveBridgeItemDown(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $question_type_id,
			$library_id);
	}
}
?>
