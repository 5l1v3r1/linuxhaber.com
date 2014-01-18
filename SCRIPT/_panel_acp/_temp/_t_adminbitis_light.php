<?php
if (!defined('yakusha')) die('...');
$endtime = microtime(true); 
$bitistime = substr(($endtime - $starttime),0,6); 

//$kullanim = memory_get_usage();
$kullanim = memory_get_peak_usage(true);
$kullanim = round($kullanim / 1024 / 1024, 2);
$sorgusayisi = $vt->sorguSayisi();
?>
<div id="page-footer">
SÜS: <?php echo $bitistime?> sayine. USG: <?php echo $kullanim?>  MB.  SQL: <?php echo $sorgusayisi?>
</div>
