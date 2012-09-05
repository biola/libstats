<?php 
include('mainHeader.php');

// display the results of the report from the [report] class in [report].php
$report_class_handle = new $_REQUEST["report_id"]();
$report_class_get = $report_class_handle->display($rInfo);
?>
<div id="csv">
        <a href="reportReturn.do?date1=<?php echo $_REQUEST["date1"]; ?>&date2=<?php echo $_REQUEST["date2"]; ?>&library_id=<?php echo $_REQUEST["library_id"]; ?>&location_id=<?php echo $_REQUEST["location_id"]; ?>&report_id=<?php echo $_REQUEST["report_id"]; ?>&csv_export=true">Export this report into Excel</a>
</div>
<?php include 'footer.php'; ?>
