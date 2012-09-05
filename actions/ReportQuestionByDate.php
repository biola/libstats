<?php
class ReportQuestionByDate extends Action {
    // This class is rather abstract, and its methods
    // are all do-nothing methods.
    // Then again, subclasses aren't that much better...

    function perform() {
        if ($_REQUEST['report_id'] == 1) {
	// make my report table header...
	echo '<table id= "questionTable">
			<tr>
				<th>Hour Begin TO End</th>
				<th>Question Count</th>
				<th>Percentage</th>
			</tr>';
	// get my report table data...
	$i = 0;
	$count = array();
	$percentage = array();
	echo '<tbody';
	foreach ($reports as $report) {
		$nextHour = ($report["hour_begin"] + 1);
		if ($nextHour == 13) {
			$nextHour = 1;
		}
		
		echo ('<tr>'.
				'<td>' . $report["hour_begin"] . ' TO ' . $nextHour . '</td>' .
				'<td>' . ($report["question_count"] + 0) . '</td>' .
				'<td>' . round(((($report["question_count"] + 0) / $numberReportQuestionCount)*100), 1) . '%' . '</td>' .
			'</tr>');
		$i++;
		$arrayCount = $i;
		$count[$arrayCount] = $report["question_count"];
		$percentage[$arrayCount] = round(((($report["question_count"] + 0) / $numberReportQuestionCount)*100), 1);	
	}
	echo '</tbody>';
	// total report summary
	$questionSum = array_sum($count);
	$questionPercentage = array_sum($percentage);
	echo ('<tr>' .
			'<td><strong>Totals</strong>' .
			'<td><strong>' . $questionSum . '</strong></td>' .
			'<td><strong>' . $questionPercentage . '%' . '</strong></td>' .
	  	'</tr></table></div></div>');
}		
		
		
		
		return perform();
    }

    function isAuthenticationRequired() {
        return true;
    }
}
?>
