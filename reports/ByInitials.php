<?php
class ByInitials extends Report {

	/**
	 * store report information: name of report and description of report
	 * @return					:	an array containing info on the report
	 */
	function info() {
		$report_info["name"] = "Questions By Initials- Question Format";
		$report_info["desc"] = "This report will provide individual statistics based on the initials
			recorded on the \"Add A Question\" page for office visits.<br />
			<b>NOTE:</b> It is important to be consistent entering your initials.";
		return ($report_info);
	}


	/**
	 * the SQL query/statement for the report
	 * @param	=		sql		:	the WHERE clause clauses
	 * @param	=		param	: parameters from the form
	 * @return					: result of perform the SQL query	
	 */
	function perform($sql, $param) {
		// don't lose the db!
		$db = $_REQUEST['db'];
		$this->db = $db;

		// gather $result
		$fullQuery = <<<QUERYSTRING
			SELECT COUNT(questions.question) as questions_count, initials,
				questions.question_format_id, question_formats.question_format,
				question_formats.description
			FROM questions
			JOIN question_formats ON
				(questions.question_format_id = question_formats.question_format_id) $sql
			GROUP BY initials, questions.question_format_id
QUERYSTRING;

		$result["data"] = $this->db->getAll($fullQuery, $param);
		$result['metadata'] = array_keys($result['data'][0]);

		return $result;
	}


	/**
	 * display the results of the report
	 * @param	=	rInfo			:	a multi-dimensional array pertaining to the report, including results
	 */
	function display($rInfo) {
		echo "<h3>{$rInfo['library_name']}";
		if (isset($rInfo['location_name'])){
			echo " > location: {$rInfo['location_name']}";
		}
    if(isset($rInfo['question_type'])){
			echo " > question type: {$rInfo['question_type']}";
		}
		if(isset($rInfo['patron_type'])){
			echo " > patron type: {$rInfo['patron_type']}";
		}
    if(isset($rInfo['question_format'])){
			echo " > question format: {$rInfo['question_format']}";
		}
		echo "</h3><h3>{$rInfo['reportList']['name']} from {$rInfo['date1']} through {$rInfo['date2']}- Full Report</h3>";

		foreach ($rInfo['reportResults']["data"] as $report) {
			$initials[$report["initials"]][$report["question_format_id"]] = $report["questions_count"];
			@$initials[$report["initials"]]["count"] += $report["questions_count"];
			$spent[$report["question_format_id"]]["spent"] = $report["question_format"];
			$spent[$report["question_format_id"]]["val"] = $report["description"];
		}

		// make my report table header...
		echo '<table id= "questionTable">
				<tr>
					<th>Initials</th>
					<th>Question Format</th>
				</tr>
				<tr><td colspan=4></td></tr>
				<tbody>
		';
		// get my report table data...
		$arrayIndex = 0;
		$count = array();
		$percentage = array();

		foreach ($initials as $index => $initial) {
			echo "<th colspan=4 align=left>$index</th>";
			foreach ($spent as $time_index => $time) {
				echo "<tr><td></td>
					<td>{$time['spent']}</td>
					<td>" . @$initial[$time_index] . "</td>
					<td></td></tr>";
					@$count_total += $initial[$time_index];
					@$time_total += $initial[$time_index] * $time["val"];
					@$count_grand += $initial[$time_index];
			}
			echo "<tr><td></td><td align=right>Totals</td>
					<td>$count_total</td>
				</tr>
				<tr><td colspan=4></td></tr>
			";
			unset($count_total);
			unset($time_total);
		}
		echo '</tbody>';
		// total report summary
		$questionSum = array_sum($count);
		$questionPercentage = array_sum($percentage);
		echo "<tr><td></td><td><strong>Grand Totals</strong></td><td><strong>$count_grand</strong></td>" .
				"</tr></table>";
	}


	/**
	 * still unclear why this is here; inherited from previous method of reporting
	 * @return						: true
	 */
	function isAuthenticationRequired() {
		return true;
	}
}
?>
