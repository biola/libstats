<?php include('content/helpHeader.php');
$helpArray = ($rInfo['helpList']);
echo('<h3>' . $helpArray['help_name'] . '</h3>---' . $helpArray['description'] . '<br />');



$optionArray = $rInfo['optionList'];
	if (!isset($optionArray)){
		$optionArray = 0;
	}
$descPatronArray = $rInfo['descPatron'];
	if(!isset($descPatronArray)){
		$descPatronArray = 0;
	}

$descQuestionTypeArray = $rInfo['descQuestionType'];
	//var_dump($descQuestionTypeArray);
	if(!isset($descQuestionTypeArray)){
		$descQuestionTypeArray = 0;
	}
	
$descLocationArray = $rInfo['descLocation'];
	if(!isset($descLocationArray)){
		$descLocationArray = 0;
	}
	
if ($rInfo['help_id'] == 1 && $descLocationArray != 0){
	echo('<br /><h3>Options: </h3>');
	echo('<ul>');
	foreach($descLocationArray as $choice){
		echo ('<li>' . $choice['location_name']);
			if (strlen($choice['description']) > 2){
				echo('---' . $choice['description'] . '</li>');
			} else {
				echo('</li>');
			}
			if (strlen($choice['examples']) > 2){
				echo('<br /><ul>
						<li>Examples: ' . $choice['examples'] . '</li>
					  </ul><br />');
			} else {
				continue;
			}
	}
	echo('</ul>');
}

if ($rInfo['help_id'] == 2 && $descPatronArray != 0){
	echo('<br /><h3>Options: </h3>');
	echo('<ul>');
	foreach($descPatronArray as $choice){
		echo ('<li>' . $choice['patron_type']);
			if (strlen($choice['description']) > 2){
				echo('---' . $choice['description'] . '</li>');
			} else {
				echo('</li>');
			}
			if (strlen($choice['examples']) > 2){
				echo('<br /><ul>
						<li>Examples: ' . $choice['examples'] . '</li>
					  </ul><br />');
			} else {
				continue;
			}
	}
	echo('</ul>');
}

if ($rInfo['help_id'] == 3 && $descQuestionTypeArray != 0){
	echo('<br /><h3>Options: </h3>');
	echo('<ul>');

	foreach($descQuestionTypeArray as $choice){
		echo ('<li>' . $choice['question_type']);
			if (strlen($choice['description']) > 2){
				echo('---' . $choice['description'] . '</li>');
			} else {
				echo('</li>');
			}
			if (strlen($choice['examples']) > 2){
				echo('<br /><ul>
						<li>Examples: ' . $choice['examples'] . '</li>
					  </ul><br />');
			} else {
				continue;
			}	
	}
	echo('</ul>');
}
echo ('<hr /><div><a href="javascript:window.close()">Close</a></div>');
?>
