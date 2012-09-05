<?php
class ByTimeOfDay extends Report {

	/**
	 * store report information: name of report and description of report
	 * @return					:	an array containing info on the report
	 */
	function info() {
		$report_info["name"] = "Questions by Time of Day";
		$report_info["desc"] = "This report provides the count of questions for each hour of the day.";
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
    $fullQuery =
			"SELECT COUNT(question) as question_count, TIME_FORMAT(question_date, '%h-%p') as hour_begin
			FROM questions
			$sql
			GROUP BY TIME_FORMAT(question_date, '%H-%p') DESC";

    $result = $this->db->getAll($fullQuery, $param);
    return $result;
	}


	/**
	 * display the results of the report
	 * @param	=	rInfo			:	a multi-dimensional array pertaining to the report, including results
	 */
	function display($rInfo) {
		#echo "<h3>rInfo</h3><pre>"; print_r($rInfo); echo "</pre>";
	
    echo "<h3>{$rInfo['library_name']}";
    if (isset($rInfo['location_name'])){
        echo " | {$rInfo['location_name']}";
    }

		// format the start and end dates
		$dateStart = (date('Ymd',strtotime($rInfo['date1'])));
		$dateEnd = (date('Ymd',strtotime($rInfo['date2'])));
    echo "</h3><h3>{$rInfo['reportList']['name']} from $dateStart through $dateEnd </h3>";    
		// ^^ the above is a standard header ^^

    // make my report table header...
    echo '<table id= "questionTable">
            <tr>
                <th>Hour Begin TO End</th>
                <th>Question Count</th>
                <th>Percentage</th>
            </tr>
						<tbody>';

    // get my report table data...
    $i = 0;
    $count = array();
    $percentage = array();
		$numberReportQuestionCount = $rInfo['reportQuestionCount'] + 0;

		// loop through to display and calculate results
    foreach ($rInfo["reportResults"] as $report) {
        $nextHour = ($report["hour_begin"] + 1);
        if ($nextHour == 13) {
            $nextHour = 1;
        }
        
        echo "<tr>
                <td>{$report["hour_begin"]} TO $nextHour </td>
                <td>" . ($report["question_count"] + 0) . "</td>
                <td>" . round(((($report["question_count"] + 0) / $numberReportQuestionCount)*100), 1) . "%</td>
            </tr>";
        $i++;
        $arrayCount = $i;
        $count[$arrayCount] = $report["question_count"];
        $percentage[$arrayCount] = round(((($report["question_count"] + 0) / $numberReportQuestionCount)*100), 1);    
    }

    // total report summary
    $questionSum = array_sum($count);
    $questionPercentage = array_sum($percentage);
    echo "</tbody>
					<tr>
            <td><strong>Totals</strong></td>
            <td><strong>$questionSum</strong></td>
            <td><strong>$questionPercentage%</strong></td>
          </tr></table>";

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
