<?php
include('mainHeader.php');

// get all the infomation about the various reports to display in a list
$report_handle = new Report();
$report_list = $report_handle->get();
sort($report_list);
?>

<h3>Please choose from these <?php echo count($report_list); ?> reports.</h3>
<?php
// loop through list and display all reports
for ($i = 0; $i < count($report_list); $i++) {
  $temp_report = new  $report_list[$i];
  $temp_info = $temp_report->info();
?>
<div class="report">
  <h3><?php echo($i + 1); ?>) <a href="reportAddDate.do?&amp;report_id=<?php echo
  $report_list[$i]; ?>"/><?php echo $temp_info["name"]; ?></a></h3>
  <div><?php echo $temp_info["desc"]; ?></div>
</div>
<?php
}
include 'footer.php';
?>

