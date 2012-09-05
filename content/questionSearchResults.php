<?php include('mainHeader.php'); ?>
<?php
$testfoo = strrchr($rInfo['origin'], '/');
$measuretestfoo = strlen($testfoo);
$killfoo = substr($testfoo, 1, $measuretestfoo);
if ($testfoo == '') {
	$forward = urlencode($rInfo['origin']);
} 	
else {
	$forward = urlencode($killfoo);
}

echo getPageNav(
    $rInfo['base_url'],
    $rInfo['list_meta']['nonemptyQuestions'],
    $rInfo['count'],
    $rInfo['page'],
    $rInfo['criteria_array']);

// A little code to define the table layout...
$tableCols = array(
    'Edit' => array(
        '<a href="questionEditForm.do?questionId=',
        'question_id',
		'&amp;origin=',
		$forward,	
		'">',
		'question_id',
		'</a>'),
    'Patron Type' => 'patron_type',
    'Question Type' => array('question_type','<br /> ', 'time_spent'),
    'Question Format' => 'question_format',
    'Location' => array('library_short_name', '<br />', 'location_name'),
    'Question / Answer<br><label id = "allQuestionsControl"><input type = "checkbox" id = "qShow" /> Show Empty Questions</label>' => 
	array (
		'<div class = "question">',
		'Q: ',
		'question',
		'</div>',
		'<div class = "answer">',
		'A: ', 
		'answer',
		'</div>'),
    'Date' => 'question_date',
    'Initials<br><label id = "initialsControl"><input type = "checkbox" id = "iShow" /> Show</label>' => array('<span class="initials">', 'initials', '</span>'));
// There are lots of magic hacks in this function
echo getQuestionTable(
    'questionTable',
    $tableCols,
    $rInfo['questionList'],
    $rInfo['searchWords']);


echo 'Found '. '<strong>' . $rInfo['list_meta']['totalQuestions'] . '</strong>' . ' total questions (' . ($rInfo['list_meta']['totalQuestions'] - $rInfo['list_meta']['nonemptyQuestions']) . ' empty).';


echo getPageNav(
    $rInfo['base_url'],
    $rInfo['list_meta']['nonemptyQuestions'],
    $rInfo['count'],
    $rInfo['page'],
    $rInfo['criteria_array']);

?>
<?php include 'footer.php'; ?>