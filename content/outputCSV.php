<?php $header_row = $rInfo['reportResults']['metadata'];

echo implode(",", $header_row)."\n";

$data = $rInfo['reportResults']['data'];
foreach ($data as $row) {
    echo implode(",", $row)."\n";
}
?>