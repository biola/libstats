<?php
require_once("../Utils.php");
$myDate = grwd('date');
$goodDate = makeDateSane($myDate);
$parsed = (strtotime($goodDate) > 0) + 0;
?>
var el_id = '<?php echo($_GET['id']); ?>';
var unparsedDate = '<?php echo($myDate); ?>';
var goodDate = '<?php echo($goodDate); ?>';
var parsed = <?php echo($parsed); ?>;

if (goodDate == '') {
    $(el_id).style.backgroundColor = '#fff';
} else {    
    if (parsed) {
        $(el_id).value = goodDate;
        new Effect.Highlight(el_id, {startcolor: '#aaeeaa', endcolor: '#ffffff', duration: .5});
    } else {
        $(el_id).style.backgroundColor = '#eaa';
    }
}