<?php
class ByTypeTime extends Report {

       /**
        * store report information: name of report and description of report
        * @return                                      :       an array containing info on the report
        */
       function info() {
               $report_info["name"] = "Questions By Type - Question Time";
               $report_info["desc"] = "This report will provide individual statistics based on the question type recorded and the average time spent.";
               return ($report_info);
       }


       /**
        * the SQL query/statement for the report
        * @param       =               sql             :       the WHERE clause clauses
        * @param       =               param   : parameters from the form
        * @return                                      : result of perform the SQL query
        */
       function perform($sql, $param) {
		// don't lose the db!
		$db = $_REQUEST['db'];
		$this->db = $db;

		// gather $result
		$fullQuery = <<<QUERYSTRING
			SELECT COUNT(questions.question) as questions_count,
				questions.question_type_id, question_types.question_type,
				questions.time_spent_id, time_spent_options.time_spent,
				time_spent_options.description
			FROM questions
			JOIN time_spent_options ON
				(questions.time_spent_id = time_spent_options.time_spent_id)
			JOIN question_types ON
				(questions.question_type_id = question_types.question_type_id) $sql
			GROUP BY questions.question_type_id, questions.time_spent_id
QUERYSTRING;

		$result["data"] = $this->db->getAll($fullQuery, $param);
		$result['metadata'] = array_keys($result['data'][0]);

		return $result;
       }


       /**
        * display the results of the report
        * @param       =       rInfo                   :       a multi-dimensional array pertaining to the report, including results
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
                       $questiontypes[$report["question_type"]][$report["time_spent_id"]] = $report["questions_count"];
                       @$questiontypes[$report["question_type"]]["count"] += $report ["questions_count"];
                       $spent[$report["time_spent_id"]]["spent"] = $report["time_spent"];
                       $spent[$report["time_spent_id"]]["val"] = $report["description"];
               }

               // make my report table header...
               echo '<table id= "questionTable">
                               <tr>
                                       <th>Question Type</th>
                                       <th>Time Spent</th>
                                       <th>Time Spent Count</th>
                                       <th>Approximate Time Spent</th>
                               </tr>
                               <tr><td colspan=4></td></tr>
                               <tbody>
               ';
               // get my report table data...
               $arrayIndex = 0;
               $count = array();
               $percentage = array();

               foreach ($questiontypes as $index => $questiontype) {
                       echo "<th colspan=4 align=left>$index</th>";
                       foreach ($spent as $time_index => $time) {
                               echo "<tr><td></td>
                                       <td>{$time['spent']}</td>
                                       <td>" . @$questiontype[$time_index] . "</td>
                                       <td>" . $time["val"] * @$questiontype[$time_index] . "</td>
                                       <td></td></tr>";
                                       @$count_total += $questiontype[$time_index];
                                       @$time_total += $questiontype[$time_index] * $time["val"];
                                       @$count_grand += $questiontype[$time_index];
                                       @$time_grand += $questiontype[$time_index] * $time["val"];
                       }
                       echo "<tr><td></td><td align=right>Totals</td>
                                       <td>$count_total</td>
                                       <td>$time_total</td>
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
               echo "<tr><td></td><td><strong>Grand Totals</strong></td><td><strong>
			$count_grand</strong></td>" .
		       "<td><strong>$time_grand</strong></td></tr></table>";
       }


       /**
        * still unclear why this is here; inherited from previous method of reporting
        * @return                                              : true
        */
       function isAuthenticationRequired() {
               return true;
       }
}
?>
