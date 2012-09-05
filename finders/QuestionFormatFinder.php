<?php
require_once 'Finder.php';
/**
 * Class info
 *
 * @author Nathan Vack <njvack@library.wisc.edu>
 */

class QuestionFormatFinder extends Finder {
    var $cols = array('question_format_id', 'question_format', 'parent_list', 'description', 'examples');
    var $table = 'question_formats';
	var $joinTable = 'library_question_formats';
    var $joinCol = 'question_format_id';
	var $joinCols = array('question_format_id', 'library_id', 'list_order');

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
			library_question_formats.library_id,
            library_question_formats.question_format_id,
            library_question_formats.list_order,
			question_formats.question_format
        FROM
            library_question_formats
		JOIN question_formats ON
			(library_question_formats.question_format_id = question_formats.question_format_id)
        WHERE 
            library_id = ?
        ORDER BY list_order";
        return $this->db->getAll($query, array($libraryId+0));
	}
	
	function getDistinctList() {
		$query =
		"SELECT
			question_format_id,
			question_format
		FROM
			question_formats
		ORDER BY
			question_format ASC";
		return $this->db->getAll($query);
	}
	
	function addOption($option_pk, $question_format, $parent_pk, $description, $examples) {
        return parent::_addOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_format,
			$parent_pk,
			$description,
			$examples);
    }
	
	function editOption($option_pk, $question_format, $parent_pk, $description, $examples) {
        return parent::_editOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_format,
			$parent_pk,
			$description,
			$examples);
    }

	function deleteOption($option_pk, $question_format, $parent_pk, $description, $examples) {
        return parent::_deleteOption(
            $this->table,
            $this->cols,
            $this->joinCol,
            $option_pk,
			$question_format,
			$parent_pk,
			$description,
			$examples);
    }
	
	function addBridgeItem($question_format_id, $library_id) {
        return parent::_addBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $question_format_id,
			$library_id);
    }	
	
	function removeBridgeItem($question_format_id, $library_id) {
        return parent::_removeBridgeItem(
            $this->joinTable,
            $this->joinCols,
            $question_format_id,
			$library_id);
    }	
	
	function findMoverLibraryID($libraryId) {
        return parent::_findMoverLibraryID(
            $this->table,
            $this->cols,
            $this->joinCol,
            $libraryId);
	}
	
	function moveBridgeItemUp($question_format_id, $library_id) {
        return parent::_moveBridgeItemUp(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $question_format_id,
			$library_id);
	}
	
	function moveBridgeItemDown($question_format_id, $library_id) {
        return parent::_moveBridgeItemDown(
            $this->table,
			$this->cols,
			$this->joinTable,
            $this->joinCol,
			$this->joinCols,
            $question_format_id,
			$library_id);
	}

}
?>