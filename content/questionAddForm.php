<?php include('mainHeader.php'); ?>
<div>
<form name = "questionForm" method = "post" action = "addQuestion.do"
    id = "qForm">

<?php $optionFunction = $rInfo['optionFunction']; ?>

<div id = "toolbar">
<div class = "inputBox">
    <h5><a href="help.do?advice=1" class="helpLink=">Location</a></h5>
    <?php echo($optionFunction(
        'location', 
        $rInfo['locationOpts'], 
        'location_id',
        'location_name',
        $rInfo['locationId'], 5)); ?>
</div>
<div class = "inputBox">
    <h5><a href="help.do?advice=2" class="helpLink=">Patron Type</a></h5>
    <?php echo($optionFunction(
        'patronType',
        $rInfo['patronTypeOpts'],
        'patron_type_id',
        'patron_type', 
        $rInfo['lastPatronType'], 5)); ?>
</div>
<div class = "inputBox">
    <h5><a href="help.do?advice=3" class="helpLink=">Question Type</a></h5>
    <?php echo($optionFunction(
        'questionType',
        $rInfo['questionTypeOpts'],
        'question_type_id',
        'question_type', 
        $rInfo['lastQuestionType'], 5)); ?>
</div>
<div class = "inputBox">
    <h5>Time Spent</h5>
    <?php echo($optionFunction(
        'timeSpent',
        $rInfo['timeSpentOpts'],
        'time_spent_id',
        'time_spent', 
        $rInfo['lastTimeSpent'], 5)); ?>
</div>
<div class = "inputBox">
    <h5>Question Format</h5>
    <?php echo($optionFunction(
        'questionFormat',
        $rInfo['questionFormatOpts'],
        'question_format_id',
        'question_format', 
        $rInfo['lastQuestionFormat'], 5)); ?>
</div>
<div class = "inputBox">
    <h5>Initials</h5>
    <input type = "text" size = "5" name = "initials" id = "initials" 
        value = "<?php echo($rInfo['lastInitials']); ?>" >
</div>
<div class = "inputBox">
    <h5><a href="help.do?advice=5" class="helpLink=">Backdate</a></h5>
    <input name = "mydate" id = "mydate" type = "text" size = "15"
    class = "validDate" />
<?php
// if calendar widget is enabled, display button
if (CAL_WIDGET == TRUE ) {
	echo "<button id=\"trigger\">...</button>";
}
?>
</div>
</div>
<div id = "qBox">
    <h5><a href="help.do?advice=4" class="helpLink=">Question</a></h5>
    <textarea name = "question" id = "question" rows = "2"></textarea>
</div>

<div id = "qAnswer">
	<h5><a href="help.do?advice=4" class="helpLink=">Answer</a></h5>
	<textarea name="answer" id="answer" rows="2"></textarea>
</div>
<div>
<input type = "submit" name = "saveButton" value = "Save Question / Answer" />
<?php if ($rInfo['lastAdded'] != null) { ?>
Last question added from this computer at <?php echo($rInfo['lastAdded']); ?>
<?php } ?>
</div>
</form>
<?php
// if calendar widget is enabled, display the javascript
if (CAL_WIDGET == TRUE ) {
?>
<!-- display javascript for calendar button -->
<script type="text/javascript">
 Calendar.setup(
  {
   inputField  : "mydate",             // ID of the input field
   ifFormat    : "%m/%d/%Y %I:%M %p",  // the date format
   button      : "trigger",            // ID of the button
   showsTime   : "true",               // show time
   timeFormat  : "12"                  // set time to 12 hours, not 24
  }
 );
</script>
<?php } ?>
</div>

<?php 
echo getPageNav(
    'questionAddForm.do',
    $rInfo['nonemptyQuestionCount'], 
    $rInfo['count'], 
    $rInfo['page']);

$forward = urlencode($rInfo['origin']);

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
    'Location' => array('location_name'),
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

// Lots of hacks in this function to get display just right
echo getQuestionTable(
    'questionTable',
    $tableCols,
    $rInfo['questionList']);
echo 'Found <strong>' . $rInfo['questionCount'] . '</strong> questions ('. ($rInfo['list_meta']['totalQuestions'] - $rInfo['list_meta']['nonemptyQuestions']). ' empty).';

?>

<?php 
echo getPageNav(
    'questionAddForm.do',
    $rInfo['nonemptyQuestionCount'], 
    $rInfo['count'], 
    $rInfo['page'] 
    );
?>

<form action = "questionAddForm.do" method = "get" id = "layoutForm">
<div>
Layout: 
<?php echo getLayoutPulldown($rInfo['layout']) ?>
<input type = "submit" value ="Go">
</div>
</form>
<?php include 'footer.php'; ?>
