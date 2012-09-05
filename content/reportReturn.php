<?php 
include('mainHeader.php');

// display the results of the report from the [report] class in [report].php
$report_class_handle = new $_REQUEST["report_id"]();
$report_class_get = $report_class_handle->display($rInfo);
?>
<div id="help">
	<a href="help.do?advice=6" class="helpLink" onclick = "showHelp(this.href); return false;">Import Data to Excel</a>
</div>
<?php include 'footer.php'; ?>
