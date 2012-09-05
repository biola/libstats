<?php require_once 'Finder.php';
class ReportFinder extends Finder {
    var $selQueryJoin = 
    "SELECT *
	FROM reports";
	
	function getReportCount() {
		$query =
		"SELECT COUNT(*)
			FROM reports";
				
		$result = $this->db->getOne($query);
		return $result;
	}

	// using the below function for percentages EL
	function getReportQuestionCount($sql, $param) {
		
		$fullQuery =
		"SELECT COUNT(question) as question_count
		FROM questions" . 
		$sql;
			
		$result = $this->db->getOne($fullQuery, $param);
		return $result + 0;
	}
	
	function getChosenReport($report_id) {
		$fullQuery =
		"SELECT *
		FROM reports
		WHERE REPORT_ID = ?";
		
		$result =  $this->db->getRow($fullQuery, array($report_id));
		return $result;
	}
}
?>
