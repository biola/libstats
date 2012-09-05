<?php
require_once 'Report.php';

    /**
     * Report for questions types
     *
     * This will select question types for the requested dates
     *
     */
class QuestionsByQuestionTypeReport extends Report {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...
    
  function info() {
		$report_info["name"] = "Questions by Question Type";
		$report_info["desc"] = "This report provides the count of questions for every question type.";
		return ($report_info);
	}

  function perform($sql, $param) {
    //echo $sql;
    // don't lose the db!
    $db = $_REQUEST['db'];
    $this->db = $db;

    // gather $result
    $fullQuery = <<<QUERYSTRING
      SELECT COUNT(questions.question) as questions, question_types.question_type
      FROM questions
      JOIN question_types ON
              (questions.question_type_id = question_types.question_type_id) $sql
      GROUP BY question_types.question_type
QUERYSTRING;

    //echo ($fullQuery);
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

    // format the start and end dates
    $dateStart = (date('Ymd',strtotime($rInfo['date1'])));
    $dateEnd = (date('Ymd',strtotime($rInfo['date2'])));

		echo "</h3><h3>{$rInfo['reportList']['name']} from $dateStart through $dateEnd- Full Report</h3>";
		// ^^ the above is a standard header ^^
  
  
    // make my report table header...
    echo '<table id= "questionTable">
            <tr>
                <th>Question Type</th>
                <th>Question Count</th>
                <th>Percentage</th>
            </tr>';

    // get my report table data...
    $arrayIndex = 0;
    $count = array();
    $percentage = array();
    $numberReportQuestionCount = $rInfo['reportQuestionCount'] + 0;
 
 echo '<tbody>';
    //loop through to display and calculate results
    foreach ($rInfo["reportResults"]["data"] as $report) {
      echo "<tr>
      <td>{$report['question_type']}</td>
      <td>" . ($report["questions"] + 0) . "</td>
      <td>" . round(((($report["questions"] + 0) / $numberReportQuestionCount)*100), 1) . "%</td></tr>";
      $arrayIndex++;
      $count[$arrayIndex] = $report["questions"];
      $percentage[$arrayIndex] = round(((($report["questions"] + 0) / $numberReportQuestionCount)*100), 1);
    }
 echo '</tbody>';

 // total report summary
 $questionSum = array_sum($count);
 $questionPercentage = array_sum($percentage);
 echo "<tr><td><strong>Totals</strong></td><td><strong>$questionSum </strong></td>" .
  "<td><strong>$questionPercentage%</strong></td></tr></table>";
}

  function isAuthenticationRequired() {
    return true;
  }
}
?>