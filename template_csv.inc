<?php
if ($rInfo['library_name'] == '') {
  $rInfo['library_name'] = 'all_libraries';
}
$filename = $rInfo['library_name'] . '.csv';
header("Content-Type: text/x-csv");
header("Content-Disposition: attachment; filename=$filename");
header("Cache-Control: no-cache");
include $rInfo['content'];

?>